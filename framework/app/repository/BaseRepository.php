<?php

abstract class BaseRepository
{
    protected $app;

    public function setApp($app)
    {
        $this->app = $app;
    }
}
