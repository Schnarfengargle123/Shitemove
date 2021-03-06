<?php

class Rentals extends BasicPage
{
    public function render()
    {
        $this->setTitle('Rentals');

        $user = '';

        $user_id = $this->getLoginInfo();
        if ($user_id != 0) {
            $user = User::getUserInfo($this->getLoginInfo());
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['transaction_id']) && strlen($_POST['transaction_id']) != 0) {
                PropertyService::removeRental($_POST['transacation_id']);
            }
        }

        Renderer::render("rentals.php", [
            'user' => $user,
            'rentals' => PropertyService::getRentalsForUser($user_id)
        ]);
    }
}
