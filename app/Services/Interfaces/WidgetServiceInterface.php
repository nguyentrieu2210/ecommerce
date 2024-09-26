<?php

namespace App\Services\Interfaces;

/**
 * Interface WidgetServiceInterface
 * @package App\Services\Interfaces
 */
interface WidgetServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
    public function getDataByWidgets ($widgets = []);
}
