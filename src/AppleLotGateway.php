<?php

class AppleLotGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM appleLot";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {       
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO appleLot (company_name, cultiver, country_of_origin, harvested_date, weight)
                VALUES (:company_name, :cultiver, :country_of_origin, :harvested_date, :weight)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":company_name", $data["company_name"], PDO::PARAM_STR);
		$stmt->bindValue(":cultiver", $data["cultiver"], PDO::PARAM_STR);
		$stmt->bindValue(":country_of_origin", $data["country_of_origin"], PDO::PARAM_STR);
        $stmt->bindValue(":harvested_date", $data["harvested_date"], PDO::PARAM_STR);
		$stmt->bindValue(":weight", $data["weight"], PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM appleLot
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE appleLot
                SET company_name = :company_name, cultiver = :cultiver, country_of_origin = :country_of_origin, harvested_date = :harvested_date, weight = :weight
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":company_name", $new["company_name"] ?? $current["company_name"], PDO::PARAM_STR);
        $stmt->bindValue(":cultiver", $new["cultiver"] ?? $current["cultiver"], PDO::PARAM_STR);
		$stmt->bindValue(":country_of_origin", $new["country_of_origin"] ?? $current["country_of_origin"], PDO::PARAM_STR);
		$stmt->bindValue(":harvested_date", $new["harvested_date"] ?? $current["harvested_date"], PDO::PARAM_STR);
		$stmt->bindValue(":weight", $new["weight"] ?? $current["weight"], PDO::PARAM_STR);
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }

}











