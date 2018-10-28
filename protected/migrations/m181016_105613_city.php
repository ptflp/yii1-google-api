<?php

class m181016_105613_city extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_city', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'description' => 'string NOT NULL',
            'place_id' => 'string NOT NULL',
            'longitude' => 'string NOT NULL',
            'latitude' => 'string NOT NULL',
		  ));
		  $this->createIndex('idxplaceid','tbl_city','place_id',true);
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