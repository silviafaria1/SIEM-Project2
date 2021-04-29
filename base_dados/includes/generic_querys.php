<?php
  function filterQuery($tableName, $field){
    return "SELECT * FROM $tableName WHERE $field = ";
  }

  function getMaxValueQuery($tableName, $field){
    return "SELECT MAX ($field) as $field from $tableName ";
  }

  function getAllRowsQuery($tableName){
    return "SELECT * FROM $tableName";
  }

  function orderByQuery($tableName, $field, $resctriction, $resctriction_field){
    if($resctriction!=false){
      return getAllRowsQuery($tableName)." WHERE $resctriction_field='$resctriction'  ORDER BY $field";
    }
    else
      return getAllRowsQuery($tableName)." ORDER BY $field";
  }

  function countQuery($tableName,$field,$resctriction_field,$resctriction_value){
    if($resctriction_field!=false){
      return "SELECT count($field) as $field
              FROM $tableName
              WHERE $resctriction_field='$resctriction_value' ";
    }
    else
      return "SELECT count($field) as $field
              FROM $tableName";
  }

  function orderByWithValueQuery($tableName, $field, $value,  $resctriction, $resctriction_field){
    if($resctriction==false){
      return "SELECT * FROM $tableName WHERE $field <= '$value'  ORDER BY $field";
    }
    else
      return "SELECT * FROM $tableName  WHERE   $field <= '$value'  AND $resctriction_field='$resctriction' ORDER BY $field";
  }

  function getArrayLength($result){
    if($result==null)
      return null;
    return pg_numrows($result);
  }

  function getElements($result, $field){
    if($result==null || $field==null)
      return null;
    $row = pg_fetch_assoc($result);

    $elements=null;
    $i=0;
    while (isset($row[$field])){
      $elements[$i]=$row[$field];
      $row = pg_fetch_assoc($result);
      $i++;
    }

    return $elements;
  }
?>
