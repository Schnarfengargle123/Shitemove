<?php

class PropertyService
{
    public static function getProperties()
    {
        $query = "SELECT * FROM properties";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProperty($id)
    {
        $query = "SELECT * FROM properties WHERE _id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPropertyDetails($id)
    {
        $query = "SELECT * FROM properties, price WHERE _id = :id and property_id = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function removeRental($id)
    {
        $query = "DELETE from transaction WHERE `_id` = :id";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    public static function getRentalsForUser($id)
    {
        $query =
            "SELECT transaction._id, 
            properties.`_id` as property_id, 
            mode, value, name, pic, 
            rent, price, first_name, last_name, 
            date_format(time, '%D %b %Y, %I:%i %p') 
            as time FROM transaction, 
            properties, user, rent, 
            where transaction.property_id = properties.`_id` 
            AND user.`_id` = transaction.user_id 
            AND rent.property_id = properties.`_id`";

        $stmt = Database::getInstance()
            ->getDb()
            ->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getRentals()
    {
        $query =
            "SELECT transaction._id, 
            properties.`_property` as property_id, 
            mode, value, name, pic, 
            rate_by_hour, rent, price, first_name, last_name, 
            date_format(time, '%D %b %Y, %I:%i %p') 
            as time FROM transaction, 
            properties, user, rent, 
            where transaction.property_id = properties.`_id` 
            AND user.`_id` = transaction.user_id 
            AND rent.property_id = properties.`_id`";

        $stmt = Database::getInstance()
            ->gerDb()
            ->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertRental($transactionArray)
    {
        $fields = ['user_id', 'property_id', 'mode', 'value'];

        $db = Database::getInstance()->getDb();

        try {
            $db->beginTransaction();

            $query = 'SELECT availability FROM properties WHERE _id = :id';
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $transactionArray[`property_id`]);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                $query = 'INSERT INTO transaction(
                    ' . implode(',', $fields) . '
                    ) VALUES(:' . implode(',:', $fields) . ')';

                $stmt = $db->prepare($query);

                $prepared_array = array();
                foreach ($fields as $field) {
                    $prepared_array[':' . $field] = @$transactionArray[$field];
                }

                $stmt->execute($prepared_array);
                $id = Database::getInstance()->getDb()->lastInsertId();
            } else {
                return 0;
            }

            $db->commit();
        } catch (PDOException $ex) {
            $db->rollBack();
            return $ex->getMessage();
        }

        return $id;
    }
}
