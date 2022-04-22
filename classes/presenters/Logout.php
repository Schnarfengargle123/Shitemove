<?php

class Logout extends BasicPage
{
    public function render()
    {
        $this->setTitle('Log Out');

        Utils::logout();
        $this->refreshStatus("logout.php");

        Renderer::render("logout.php");
    }
}
