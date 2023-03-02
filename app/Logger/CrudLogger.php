<?php

namespace App\Logger;

class CrudLogger extends SystemLogger
{
  protected static $module;

  const GET = 'all';
  const CREATE = 'create';
  const UPDATE = 'update';
  const DELETE = 'delete';
  const FIRST_OR_CREATE = 'firstOrCreate';

  protected static function getModule()
  {
    return static::$module;
  }

  public static function setModule($module)
  {
    static::$module = $module;
  }

  public static function beforeCreate($data)
  {
    parent::info(self::CREATE, $data, 'Before creating ' . static::getModule());
  }

  public static function create($id)
  {
    parent::info(self::CREATE, ['id' => $id], static::getModule() . ' successfully created');
  }

  public static function errorCreate($data)
  {
    parent::error(self::CREATE, $data, 'Error creating ' . static::getModule());
  }


  public static function beforeGet($data)
  {
    parent::info(self::GET, $data, 'Before getting ' . static::getModule());
  }

  public static function get($data)
  {
    $logData = $data;
    $ids = [];

    if ($data instanceof \Illuminate\Support\Collection) {
      $logData = $data->toArray();
    }

    if (isset($logData['data'])) {
      $logData = $logData['data'];

      if ($logData instanceof \Illuminate\Support\Collection) {
        $logData = $logData->toArray();
      }
    }

    if (is_array($logData)) {
      $ids = array_column($logData, 'id');
    }

    parent::info(self::GET, ['ids' => $ids], 'Successfully get ' . static::getModule());
  }

  public static function errorGet($data)
  {
    parent::error(self::GET, $data, 'Error getting ' . static::getModule());
  }

  public static function beforeFirstOrCreate($data)
  {
    parent::info(self::FIRST_OR_CREATE, $data, 'Before finding/creating ' . static::getModule());
  }

  public static function firstOrCreate($id)
  {
    parent::info(self::FIRST_OR_CREATE, ['id' => $id], static::getModule() . ' successfully found/created');
  }

  public static function errorFirstOrCreate($data)
  {
    parent::error(self::FIRST_OR_CREATE, $data, 'Error finding/creating ' . static::getModule());
  }

  public static function beforeUpdateByAttribute($attribute, $value)
  {
    parent::info(self::UPDATE, [$attribute => $value], 'Before updating ' . static::getModule());
  }

  public static function updateByAttribute($attribute, $value)
  {
    parent::info(self::UPDATE, [$attribute => $value], static::getModule() . ' successfully updated');
  }

  public static function errorUpdateByAttribute($attribute, $value)
  {
    parent::error(self::UPDATE, [$attribute => $value], 'Error updating ' . static::getModule());
  }

  public static function beforeDeleteByAttribute($attribute, $value)
  {
    parent::info(self::DELETE, [$attribute => $value], 'Before deleting ' . static::getModule());
  }

  public static function deleteByAttribute($attribute, $value)
  {
    parent::info(self::DELETE, [$attribute => $value], static::getModule() . ' successfully deleted');
  }

  public static function errorDeleteByAttribute($attribute, $value)
  {
    parent::error(self::DELETE, [$attribute => $value], 'Error deleting ' . static::getModule());
  }

  public static function beforeDeleteByAttributes(array $attributes)
  {
    parent::info(self::DELETE, $attributes, 'Before deleting ' . static::getModule());
  }

  public static function deleteByAttributes(array $attributes)
  {
    parent::info(self::DELETE, $attributes, static::getModule() . ' successfully deleted');
  }

  public static function errorDeleteByAttributes(array $attributes)
  {
    parent::error(self::DELETE, $attributes, 'Error deleting ' . static::getModule());
  }
}
