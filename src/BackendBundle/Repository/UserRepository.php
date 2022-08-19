<?php

namespace BackendBundle\Repository;

use BackendBundle\Entity\Following;
use BackendBundle\Entity\User;

class UserRepository extends \Doctrine\ORM\EntityRepository{

    public function getFollowingUsers($user){
        $em                 = $this->getEntityManager();
        $following_repo     = $em->getRepository(Following::class);
        $following          = $following_repo->findBy(["user"=>$user]);

        $following_array    = array();
        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        $user_repo          = $em->getRepository(User::class);
        $users              = $user_repo->createQueryBuilder('u')
                            ->where("u.id != :user AND u.id IN (:following)")
                            ->setParameter("user", $user->getId())
                            ->setParameter("following", $following_array)
                            ->orderBy("u.id", "DESC");

        return $users;
    }

}