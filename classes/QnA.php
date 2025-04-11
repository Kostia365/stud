<?php

namespace formular;

require_once('db/config.php');
use PDO;
class Kontakt
{
    private $conn;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $config = DATABASE;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->conn = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' . $config['DBNAME'] . ';port=' . $config['PORT'], $config['USER_NAME'], $config['PASSWORD'], $options);
        } catch (PDOException $e) {
            die("Chyba pripojenia: " . $e->getMessage());
        }
    }
    }
namespace otazkyodpovede;
define('__ROOT__', dirname(dirname(__FILE__)));
require_once (__ROOT__.'/db/config.php');

    use PDO;

class QnA


{
    private $conn;

    public function __construct()
    {
        $this->connect();
    }
    public function getQnA() {
        // SQL SELECT príkaz
        $sql = "SELECT * FROM qna";
        $statement = $this->conn->prepare($sql);
        $statement->execute();
        // Získanie dát
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        // Zobrazenie otázok a odpovedí
        if ($data) {
            echo '<section class="container">';
            foreach ($data as $row) {
                echo '<div class="accordion">
                        <div class="question">' .
                    $row["otazka"] . '
                         </div>
                        <div class="answer">' .
                    $row["odpoved"] . '
                        </div>
                </div>';
            }
            echo '</section>';
        } else {
            echo "Neboli nájdené žiadne otázky a odpovede.";
        }
    }
    private function connect()
    {
        $config = DATABASE;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        );
        try {
            $this->conn = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' .
                $config['DBNAME'] . ';port=' . $config['PORT'], $config['USER_NAME'],
                $config['PASSWORD'], $options);
        } catch (PDOException $e) {
            die("Chyba pripojenia: " . $e->getMessage());
        }
    }
    public function insertQnA()
    {
        try {
            // Načítanie JSON súboru
            $data = json_decode(file_get_contents
            (__ROOT__ . '/data/datas.json'), true);
            $otazky = $data["otazky"];
            $odpovede = $data["odpovede"];
            // Vloženie otázok a odpovedí v rámci transakcie
            $this->conn->beginTransaction();
            $sql = "INSERT INTO qna (otazka, odpoved) VALUES (:otazka, :odpoved)";
            $statement = $this->conn->prepare($sql);
            for ($i = 0; $i < count($otazky); $i++) {
                $statement->bindParam(':otazka', $otazky[$i]);
                $statement->bindParam(':odpoved', $odpovede[$i]);
                $statement->execute();
            }
            $this->conn->commit();
            echo "Dáta boli vložené";
        } catch (Exception $e) {
            // Zobrazenie chybového hlásenia
            echo "Chyba pri vkladaní dát do databázy: " . $e->getMessage();
            $this->conn->rollback(); // Vrátenie späť zmien v prípade chyby
        } finally {
            //            // Uzatvorenie spojenia
            $this->conn = null;
        }
    }
}