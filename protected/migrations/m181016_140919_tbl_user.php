<?php

class m181016_140919_tbl_user extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_user', array(
				'id' => 'pk',
				'email' => 'string NOT NULL',
				'avatar' =>  'string DEFAULT NULL',
				'city_id' =>  'integer DEFAULT NULL',
				'role' => 'integer NOT NULL',
				'ban' => 'boolean NOT NULL',
				'updated_at' => 'timestamp DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
				'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        ));
	}

	public function down()
	{
		$this->dropTable('tbl_user');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}