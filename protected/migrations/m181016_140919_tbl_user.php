<?php

class m181016_140919_tbl_user extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_user', array(
            'id' => 'pk',
            'email' => 'string NOT NULL',
            'gToken' => 'string NOT NULL',
            'gUserId' => 'string NOT NULL',
        ));
	}

	public function down()
	{
		echo "m181016_140919_tbl_user does not support migration down.\n";
		return false;
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