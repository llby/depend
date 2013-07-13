<?php

namespace Depend;
require_once( APPPATH .'modules/depend/classes/helper/entity_util.php');
class Controller_Depend extends \Controller_Base
{
	public function action_index()
	{
		$usr = \Auth::get_user_id();
		$data['entities'] = \Depend\Model_Entity::find('all', array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
		));
		$this->template->title = "Entities";
		$this->template->content = \View::forge('index', $data);
	}

	public function action_depend()
	{
		$usr = \Auth::get_user_id();
		$data['entities'] = \Depend\Model_Entity::find('all', array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
		));
		$data['depends'] = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				array( 'entity.user_id', '=', $usr[1] ),
			),
			'related' => array('entity'),
			'order_by' => array(array('depend_entity_id','asc'),array('level','asc')),
		));
		$data['dependcolumn'] = \Depend\Model_Dependcolumn::find('all',array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
			'order_by' => array('level' => 'asc'),
		));

		// テーブルの中で一番下位のもの
		$query = \Depend\Model_Dependtable::query()->where('entity.user_id', $usr[1])->related('entity')->order_by('depend_entity_id', 'asc');
		$data['depends_max'] = $query->max('level');

		// カラムの中で一番下位のものと比較
		$query = \Depend\Model_Dependcolumn::query()->where('user_id', $usr[1]);
		if ( $data['depends_max'] < $query->max('level') )
		{
			$data['depends_max'] = $query->max('level');
		}

		if ( isset($this->template->err))
		{
			$data['err'] = $this->template->err;
		}
		$this->template->title = "Depends";
		$this->template->content = \View::forge('depend', $data);
	}

	public function action_dependmk()
	{
		// dependtable、dependcolumnを事前に削除する
		$usr = \Auth::get_user_id();

		$depends = \Depend\Model_Dependtable::find('all', array(
			'where' => array(
				array( 'entity.user_id', '=', $usr[1] ),
			),
			'related' => array('entity'),
		));
		foreach( $depends as $val )
		{
			$val->delete();
		}
		$dependcolumn = \Depend\Model_Dependcolumn::find('all',array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
		));
		foreach( $dependcolumn as $val )
		{
			$val->delete();
		}

		// 依存表の作成
		$entities = \Depend\Model_Entity::find('all', array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
		));
		$entitycallers = \Depend\Model_Entitycaller::find('all', array(
			'where' => array(
				array( 'entity.user_id', '=', $usr[1] ),
			),
			'related' => array('entity'),
		));

		foreach( $entities as $ent ) {

			foreach( $entitycallers as $enc ) {

				if ( $enc->name == $ent->name ) {
					$dependtable = \Depend\Model_Dependtable::forge(array(
						'depend_entity_id' => $ent->id,
						'entity_id' => $enc->entity_id,
						'level' => 0,
					));
					if ($dependtable) {
						$dependtable->save();
					}
				}
			}
			// 自分を入れる
			$dependtable = \Depend\Model_Dependtable::forge(array(
				'depend_entity_id' => $ent->id,
				'entity_id' => $ent->id,
				'level' => 0,
			));
			if ($dependtable) {
				$dependtable->save();
			}
		}

		\Response::redirect('depend/depend/depend');
	}

	public function action_dependup()
	{
		// entitycallerに追加、削除されたものをdependtableに反映する
		// dependcolumn、dependtableを削除せずに最低限の更新をする
		$this->template->debug = array();

		$usr = \Auth::get_user_id();
		// 依存表の作成
		$entities = \Depend\Model_Entity::find('all', array(
			'where' => array(
				 array( 'user_id', '=', $usr[1] )
			),
		));
		$entitycallers = \Depend\Model_Entitycaller::find('all', array(
			'where' => array(
				array( 'entity.user_id', '=', $usr[1] ),
			),
			'related' => array('entity'),
		));

		foreach( $entities as $ent ) {

			$entity_ids = array();
			foreach( $entitycallers as $enc ) {

				if ( $enc->name == $ent->name ) {
					$entity_ids[] = $enc->entity_id;
					if ( !\Depend\Model_Dependtable::is_exist($ent->id,$enc->entity_id) ) {
						$dependtable = \Depend\Model_Dependtable::forge(array(
							'depend_entity_id' => $ent->id,
							'entity_id' => $enc->entity_id,
							'level' => 0,
						));
						if ($dependtable) {
							$dependtable->save();
						}
					}

				}
			}

			// 自分を入れる
			$entity_ids[] = $ent->id;
			if ( !\Depend\Model_Dependtable::is_exist($ent->id,$ent->id) ) {
				$dependtable = \Depend\Model_Dependtable::forge(array(
					'depend_entity_id' => $ent->id,
					'entity_id' => $ent->id,
					'level' => 0,
				));
				if ($dependtable) {
					$dependtable->save();
				}
			}

			// 一つも登録がなければ削除する
			$dependtable = \Depend\Model_Dependtable::find('all', array(
				'where' => array(
					array( 'depend_entity_id', '=', $ent->id ),
					array( 'entity_id', 'NOT IN', $entity_ids),
				),
			));
			foreach( $dependtable as $dt ) {
				$dt->delete();
			}
		}
		\Response::redirect('depend/depend/depend');
	}

	public function action_dependchk()
	{
		$usr = \Auth::get_user_id();
		$this->template->err = array();
		$this->template->err['depends'] = \Depend\Helper_Entity_Util::chk_1($usr[1]);
		$this->template->err['entities'] = \Depend\Helper_Entity_Util::chk_2($usr[1]);
		$this->action_depend();
	}

	public function action_dependmv()
	{
		$depend = \Depend\Model_Dependtable::find(\Input::post('id'));
		$up = \Input::post('up');
		if (isset($up))
		{
			$depend->level += 1;
			$depend->save();
		} elseif ( $depend->level > 0 ) {
			$depend->level -= 1;
			$depend->save();
		}
		\Response::redirect('depend/depend/depend');
	}

	public function action_columnlfrg( $level, $lf, $rg )
	{
		if (isset($lf)) {
			\Depend\Model_Dependtable::level_updw( 0, $level );
			\Depend\Model_Dependcolumn::level_updw( 0, $level );
		} elseif (isset($rg)) {
			\Depend\Model_Dependtable::level_updw( 1, $level );
			\Depend\Model_Dependcolumn::level_updw( 1, $level );
		}
	}

	public function action_columnadd()
	{
		$level = \Input::post('level');
		$lf = \Input::post('lf');
		$rg = \Input::post('rg');
		if (isset($lf) || isset($rg)) {
			$this->action_columnlfrg($level, $lf, $rg);
		} else {
			$usr = \Auth::get_user_id();
			$col = \Depend\Model_Dependcolumn::forge(array(
				'user_id' => $usr[1],
				'level' => $level,
				'name' => \Input::post('name'),
				'contents' => \Input::post('contents'),
				'comment' => '',
			));
			$col->save();
		}
		\Response::redirect('depend/depend/depend');
	}

	public function action_columnup()
	{
		$col = \Depend\Model_Dependcolumn::find(\Input::post('id'));
		$up = \Input::post('up');
		$dl = \Input::post('dl');
		$lf = \Input::post('lf');
		$rg = \Input::post('rg');
		if (isset($up))
		{
			$col->name = \Input::post('name');
			$col->contents = \Input::post('contents');
			$col->comment = '';
			$col->save();
		} elseif (isset($dl)) {
			$col->delete();
		} elseif (isset($lf) || isset($rg)) {
			$this->action_columnlfrg($col->level, $lf, $rg);
		}
		\Response::redirect('depend/depend/depend');
	}

	public function action_view($id = null)
	{
		$data['entity'] = \Depend\Model_Entity::find($id, array(
			'related' => array('entitycaller'),
		));

		$usr = \Auth::get_user_id();
		$data['err']['entitycallers'] = \Depend\Helper_Entity_Util::chk_3($id, $usr[1]);
		$this->template->title = "Entity";
		$this->template->content = \View::forge('view', $data);
	}

	public function action_create()
	{
		if (\Input::method() == 'POST')
		{
			$val = \Depend\Model_Entity::validate('create');

			if ($val->run())
			{
				$usr = \Auth::get_user_id();
				$entity = \Depend\Model_Entity::forge(array(
					'user_id' => $usr[1],
					'name' => \Input::post('name'),
					'comment' => \Input::post('comment'),
				));

				if ($entity and $entity->save())
				{
					// 呼び出し元を登録する
					$entitycallers = \Depend\Model_Entitycaller::regist_caller($entity->id, \Input::post('entitycaller'));

					\Session::set_flash('success', e('Added entity #'.$entity->id.'.'));

					\Response::redirect('depend/depend');
				}

				else
				{
					\Session::set_flash('error', e('Could not save entity.'));
				}
			}
			else
			{
				\Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Entities";
		$this->template->content = \View::forge('create');

	}

	public function action_edit($id = null)
	{
		$entity = \Depend\Model_Entity::find($id, array(
			'related' => array('entitycaller'),
		));
		$val = \Depend\Model_Entity::validate('edit');

		if ($val->run())
		{
			$usr = \Auth::get_user_id();
			$entity->user_id = $usr[1];
			$entity->name = \Input::post('name');
			$entity->comment = \Input::post('comment');

			if ($entity->save())
			{
				// 呼び出し元を登録する
				$entitycallers = \Depend\Model_Entitycaller::regist_caller($id, \Input::post('entitycaller'));

				\Session::set_flash('success', e('Updated entity #' . $id));

				\Response::redirect('depend');
			}

			else
			{
				\Session::set_flash('error', e('Could not update entity #' . $id));
			}
		}

		else
		{
			if (\Input::method() == 'POST')
			{
				$entity->user_id = $val->validated('user_id');
				$entity->name = $val->validated('name');
				$entity->comment = $val->validated('comment');

				\Session::set_flash('error', $val->error());
			}

			else
			{
				$entity->entitycaller = \Depend\Model_Entitycaller::get_names_string( $entity->entitycaller );
			}

			$this->template->set_global('entity', $entity, false);
		}

		$usr = \Auth::get_user_id();
		$data['err']['entitycallers'] = Helper_Entity_Util::chk_3($id, $usr[1]);
		$this->template->title = "Entity";
		$this->template->content = \View::forge('edit', $data);

	}

	public function action_delete($id = null)
	{
		if ($entity = \Depend\Model_Entity::find($id))
		{
			// 先にcallersを消す
			\Depend\Model_Entitycaller::delete_by_entity( $id );

			$entity->delete();

			\Session::set_flash('success', e('Deleted entity #'.$id));
		}

		else
		{
			\Session::set_flash('error', e('Could not delete entity #'.$id));
		}

		\Response::redirect('depend');

	}
}
