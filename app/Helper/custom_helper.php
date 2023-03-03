<?php

function currency($numeric)
{
    return number_format($numeric, 0, ',', '.');
}