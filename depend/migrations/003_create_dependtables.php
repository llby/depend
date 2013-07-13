<?php

namespace Fuel\Migrations;

class Create_dependtables
{
	public function up()
	{
		\DBUtil::create_table('dependtables', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'entity_id' => array('constraint' => 11, 'type' => 'int'),
			'depend_entity_id' => array('constraint' => 11, 'type' => 'int'),
			'level' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('dependtables');
	}
}