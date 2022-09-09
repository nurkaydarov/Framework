<?php

namespace app\migrations;

use app\core\Application;

class m0003_add_column_password_to_users_table
{
    public function up()
    {
        $db = Application::$app->database;
        $SQL = "ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL";
        $db->pdo->exec($SQL);

    }

    public function down()
    {
        $db = Application::$app->database;
        $SQL = "ALTER TABLE users DROP COLUMN password VARCHAR(255) NOT NULL";
        $db->pdo->exec($SQL);
    }
}