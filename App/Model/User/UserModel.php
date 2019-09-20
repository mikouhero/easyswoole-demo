<?php 
namespace App\Model\User;

use App\Model\BaseModel;

class UserModel extends BaseModel
{

    protected $table            = 'user_list';
    protected $primaryKey       = 'id';

    public function getAll(int $page = 1, string $keyword = null,int $pageSize = 10) :array
    {

        if (!empty($keyword)) {
            $this->getDbConnection()->where('userAccount','%' . $keyword . '%','like');
        } 

        $list = $this->getDbConnection()
                    ->withTotalCount()
                    ->orderBy($this->primaryKey,'DESC')
                    ->get($this->table, [$pageSize * ($page  - 1), $pageSize]);

         $total = $this->getDbConnection()->getTotalCount();

         return ['total' => $total, 'list' => $list];

    }

    public function login(UserBean $userBean): ?UserBean
    {

        $user = $this->getDbConnection()
            ->where('userAccount', $userBean->getUserAccount())
            ->where('userPassword', $userBean->getUserPassword())
            ->getOne($this->table);
        
        if (empty($user)) {
            return null;
        }
       $user['userId'] = $user['id'];
       unset($user['id']);
        return new UserBean($user);
    }

    public function update(UserBean $userBean ,array $data) :bool
    {
        if (empty($data)) {
            return false;
        }

        return  $this->getDbConnection()
                    ->where($this->primaryKey,$userBean->getUserId())
                    ->update($this->table,$data);
              
    }

    public function getOneBySession($session)
    {   
        $user = $this->getDbConnection()
            ->where('userSession',$session)
            ->getOne($this->table);

        if (empty($user)) {
            return  null;
        }
        $user['userId'] = $user['id'];
        return new UserBean($user);
    }

    public function logout(UserBean $userBean)
    {
        $update = [
            'userSession' => '',
        ];

        return $this->getDbConnection()
                    ->where($this->primaryKey,$userBean->getUserId())
                    ->update($this->table,$update);
    }

}