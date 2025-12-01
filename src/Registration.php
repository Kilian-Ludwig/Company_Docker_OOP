<?php
class Registration {
    private array $errors = [];
    public function validatePassword($password): bool {
        if (strlen($password) < 8) {
            $this->errors[] = "Das Passwort muss mindestens 8 Zeichen lang sein.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors[] = "Das Passwort muss mindestens einen Großbuchstaben enthalten.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $this->errors[] = "Das Passwort muss mindestens einen Kleinbuchstaben enthalten.";
        }
        if (!preg_match('/\d/', $password)) {
            $this->errors[] = "Das Passwort muss mindestens eine Zahl enthalten.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $this->errors[] = "Das Passwort muss mindestens ein Sonderzeichen enthalten.";
        }
        if (preg_match('/\s/', $password)) {
            $this->errors[] = "Das Passwort darf keine Leerzeichen enthalten.";
        }
        return empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function register($email, $password, $fName) {
        $this->validatePassword($password);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Ungültige E-Mail-Adresse.";
        }
        if (empty($this->errors)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

        }
        else {
            return $this->getErrors();
        }
        return true;
    }
}