<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/15
 * Time : 10:07
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\models;


use common\entity\VolunteerTrip;

class VolunteerDb extends ProxyDb{



    public function getList($page, $title,$countryId,$cityId, $status)
    {
        $sql = sprintf("
            FROM volunteer_trip v
            LEFT JOIN country AS c ON c.id=v.countryId
            LEFT JOIN city AS ci ON ci.id=v.cityId
            WHERE 1=1
        ");
        $this->setParam("status", $status);
        if (!empty($countryId) || !empty($cityId)) {
            if (!empty($countryId)) {
                $sql .= " AND v.countryId in(".$countryId.")";
            }
            if (!empty($cityId)) {
                $sql .= " AND v.cityId in(".$cityId.")";
            }
        } else {
            if (!empty($title)) {
                $sql .= " AND v.title like :title ";
                $this->setParam("title", "%" . $title . "%");
            }
        }

        if (!empty($status) ) {
            $sql .= " AND v.status=:status ";
        }else{
            $sql .= " AND v.status!=:status ";
            $status=VolunteerTrip::VOLUNTEER_STATUS_DELETE;
        }
        $this->setParam("status", $status);


        $this->setSql($sql);
        $this->setSelectInfo("v.*,c.cname AS countryCname,c.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname");
        return $this->find($page);
    }


    public function findById($id)
    {
        $sql = sprintf("
            SELECT v.*,c.cname AS countryCname,c.ename AS countryEname,ci.cname AS cityCname,ci.ename AS cityEname FROM volunteer_trip v
            LEFT JOIN country AS c ON c.id=v.countryId
            LEFT JOIN city AS ci ON ci.id=v.cityId
            WHERE v.volunteerId=:id
        ");

        $connection=$this->getConnection();
        $command=$connection->createCommand($sql);
        $command->bindValue(":id",$id);

        return $command->queryOne();
    }

}