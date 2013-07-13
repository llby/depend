<?php

namespace Fuel\Migrations;

class Create_dependcolumns
{
	public function up()
	{
		\DBUtil::create_table('dependcolumns', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'level' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'contents' => array('type' => 'text'),
			'comment' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('dependcolumns');
	}
}