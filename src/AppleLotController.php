<?php

class AppleLotController
{
    public function __construct(private AppleLotGateway $gateway)
    {
    }
    
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            
            $this->processResourceRequest($method, $id);
            
        } else {
            
            $this->processCollectionRequest($method);
            
        }
    }
    
    private function processResourceRequest(string $method, string $id): void
    {
        $appleLot = $this->gateway->get($id);
        
        if ( ! $appleLot) {
            http_response_code(404);
            echo json_encode(["message" => "AppleLot not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($appleLot);
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($appleLot, $data);
                
                echo json_encode([
                    "message" => "AppleLot $id updated",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, PATCH");
        }
    }
    
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $id = $this->gateway->create($data);
                
                http_response_code(201);
                echo json_encode([
                    "message" => "AppleLot created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
    
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["company_name"])) {
            $errors[] = "Company Name";
        }
        
        if (empty($data["cultiver"])) {
            $errors[] = "Cultiver";
        }
		
		if (empty($data["country_of_origin"])) {
            $errors[] = "Country of Origin";
        }
		
		if (empty($data["harvested_date"])) {
            $errors[] = "Harvested Date";
        }
		if (empty($data["weight"]) || (int)$data["weight"]<1000) {
            $errors[] = "Weight is required and should be greater than 1000";
        }
        
        return $errors;
    }
}









