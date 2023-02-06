<?php
// 1. INSERT: function insertAddress
// 2. DELETE: deleteAddress
// 3. UPDATE: function changeAddressType. eg. change address type from 'home' => 'company'
// 4. SELECTION: retrieve method
// 5. PROJECTION: retrieve method

// Note:
// 0. fix the problem Alice told me this morning on data.sql: user to address is one to many mapping.
// 1. all methods listed in data_retrieve_helper.php
// 2. calling convention is instructed on the top of data_retrieve_helper.php
// 3. please let me know if I miss any function and you need more functions



//calling convention example: 

/*
require_once("data_retrive_helper.php");
if (connectToDB()) {
    $command = restaurantsFilter(3);
    $result = executePlainSQL($command);

    printResult($result);
    disconnectFromDB();
}
*/

function restaurantsFilter($rate){ 
    $sql = "SELECT restaurantID, name, rating
          FROM Restaurant
          WHERE rating  > $rate
             ";
    return $sql;
}

function retrieveDiscountCodeFromRestaurants($restaurantID){
    $sql = "SELECT promotionID
          FROM RestaurantSpecificDiscount
          WHERE restaurantID = $restaurantID
             ";
    return $sql;
 }

 function retriveAllDiscountCode(){
    $sql = "SELECT promotionID
          FROM RestaurantSpecificDiscount
          ";
    return $sql;
 }

 function retriveAllDiliveryPersonName(){
    $sql = "
       SELECT name
       FROM DeliveryPerson
    ";
    return $sql;
 }



 function retriveCarNumberPlate($personID){
    $sql = "
       SELECT number_plate
       FROM UsesVehicle
       WHERE deliveryPersonID = $personID
    ";
    return $sql;
 }

 function retriveCarModel($num_plate){
    $sql = "
       SELECT model
       FROM Vehicle
       WHERE number_plate = $num_plate
    ";
    return $sql;
 }

 function retriveUserAddress($ID){
   $sql = "
       SELECT streetAddress, city, country, addressType
       FROM UserAddress
       WHERE userID  = $ID
    ";
    return $sql;
 }
 function changeAddressType($ID, $oldStreet, $oldCity, $oldCountry, $addressType){
   $oldStreet = "'$oldStreet'";
   $oldCity = "'$oldCity'";
   $oldCountry = "'$oldCountry'";
   $addressType = "'$addressType'";
   $sql = "
       UPDATE UserAddress
       SET addressType = $addressType
       WHERE userID  = $ID AND streetAddress = $oldStreet AND city = $oldCity AND country = $oldCountry
    ";
    return $sql;
 }



 function insertAddress($ID, $newCountry, $newCity, $newStreet, $addressType){
   $newStreet = "'$newStreet'";
   $newCity = "'$newCity'"; 
   $newCountry = "'$newCountry'";
   $addressType = "'$addressType'";
    $sql = "INSERT into UserAddress
    VALUES($newStreet, $newCity, $newCountry, $addressType, $ID)
    ";
   return $sql;
 }

 function deleteAddress($ID, $oldStreet, $oldCity, $oldCountry){
   $oldStreet = "'$oldStreet'";
   $oldCity = "'$oldCity'";
   $oldCountry = "'$oldCountry'";
   $sql = "
    DELETE FROM UserAddress
    WHERE userID  = $ID AND streetAddress = $oldStreet AND city = $oldCity AND country = $oldCountry
    ";
    return $sql;
 }

?>