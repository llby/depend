<?php

namespace Fuel\Migrations;

class Create_entitycallers
{
	public function up()
	{
		\DBUtil::create_table('entitycallers', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'entity_id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('entitycallers');
	}
}