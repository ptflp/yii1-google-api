<?php

class m181016_105613_city extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_city', array(
            'id' => 'pk',
            'city' => 'string NOT NULL',
        ));
	}

	public function down()
	{
		$this->dropTable('tbl_city');
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