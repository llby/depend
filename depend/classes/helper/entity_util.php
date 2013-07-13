<?php

namespace Depend;
class Helper_Entity_Util
{
	public static function chk_1($id)
	{
		// 依存の階層レベルが同じであるかのチェック
		$depends = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				array( 'entity.user_id', '=', $id ),
			),
			'related' => array('entity'),
			'order_by' => array(array('entity_id','asc'),array('level','asc')),
		));

		$tmp_id = -1;
		$tmp_level = -1;
		foreach ( $depends as $key => $depend )
		{
			if ( $tmp_id == $depend->entity_id )
			{
				if ( $tmp_level == $depend->level )
				{
					// 同じレベルである
				} else {
					// 同じレベルになっていない
					$depends[$key]['errkind'] = 0;
				}
			}
			$tmp_id = $depend->entity_id;
			$tmp_level = $depend->level;
		}

		return $depends;
	}

	public static function chk_2($id)
	{
		// 自分が依存の階層レベルの一番上にあるかのチェック
		$entities = \Depend\Model_Entity::find('all', array(
			'where' => array(
				 array( 'user_id', '=', $id )
			),
		));

		foreach( $entities as $key => $ent )
		{
			$sql  = 'SELECT MIN(level) ';
			$sql .= 'FROM `ap_dependtables` ';
			$sql .= "WHERE `depend_entity_id` = {$ent->id} ";
			$sql .= 'GROUP BY depend_entity_id';
			$res1 = \DB::query( $sql )->execute();

			$sql  = 'SELECT * ';
			$sql .= 'FROM `ap_dependtables` ';
			$sql .= "WHERE `depend_entity_id` = {$ent->id} ";
			$sql .= "AND `level` = {$res1[0]['MIN(level)']} ";
			$res3 = \DB::query( $sql )->as_object('\Depend\Model_Dependtable')->execute();
			
			$sql  = 'SELECT * ';
			$sql .= 'FROM `ap_dependtables` ';
			$sql .= 'WHERE `depend_entity_id` = `entity_id` ';
			$sql .= "AND `entity_id` = {$ent->id} ";
			$res2 = \DB::query( $sql )->as_object('\Depend\Model_Dependtable')->execute();
			
			if ( $res1[0]['MIN(level)'] != $res2[0]->level )
			{
				$entities[$key]['errkind'] = 0;
			}
			else if ( count($res3) > 1 )
			{
				$entities[$key]['errkind'] = 1;
			}

		}
		return $entities;
	}

	public static function chk_3($id, $usr_id)
	{
		// 呼び出し元がentityに登録されているかのチェック
		$entitycallers = \Depend\Model_Entitycaller::find('all', array(
			'where' => array(
				array( 'entity_id', '=', $id ),
			),
		));
		foreach( $entitycallers as $key => $enc ) {
			$entities = \Depend\Model_Entity::find('all', array(
				'where' => array(
					 array( 'user_id', '=', $usr_id ),
					 array( 'name', '=', $enc->name ),
				),
			));
			if ( !$entities ) {
				$entitycallers[$key]['errkind']	= 0;
			}
		}
		return $entitycallers;
	}
}
