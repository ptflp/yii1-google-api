<?php

class m181016_105613_city extends CDbMigration
{
	public function up()
	{
        $this->createTable('tbl_city', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
		  ));
		  $this->createIndex('idxname','tbl_city','name',true);
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