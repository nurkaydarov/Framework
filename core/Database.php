<?php

namespace app\core;

class Database
{
    public \PDO $pdo;
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->creatingMigationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_PATH . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations); // Возвратит только те значения $files которых нет в $appliedMigrations
/*        echo '<pre>';
        var_dump($toApplyMigrations);
        echo '</pre>';*/
        foreach ($toApplyMigrations as $migration)
        {
            if($migration === '.' || $migration === '..')
            {
                continue;
            }
            require_once Application::$ROOT_PATH . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $className = "\\app\\migrations\\".$className; // namespace of className (\app\migrations\m0001_initial)
            $instance = new $className();
            echo "Applying migrations $className" . PHP_EOL;
            $instance->up();
            echo "Applied migration $className " . PHP_EOL;
            $newMigrations[] = $migration;
/*            echo '<pre>';
            var_dump($className);
            echo '</pre>';*/
        }

        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }
        else{
            echo $this->log("All Migrations are applied");
        }
/*        echo '<pre>';
        var_dump($files);
        echo '</pre>';*/
        exit();
    }

    public function creatingMigationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statment = $this->pdo->prepare("SELECT migration FROM migrations");
        $statment->execute();
        return $statment->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {

        $str = implode(',', array_map(fn($migration) => ("('$migration')") , $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    protected function log($message)
    {
        echo "[" . date('Y-m-d H:i:s') ."] - " . $message . PHP_EOL;
    }
}