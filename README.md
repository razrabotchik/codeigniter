# Библиотека CodeIgniter для работы с базой данных #

### Пример использования ###

Пример использования в файле some_model.php

####Добавление записи в базу данных:####

$data = array(
    'name' => 'Some name'
);
<br />
$this->some_model->create($data);


####Удаление записи из базы данных:####
<br />
$this->some_model->delete(2);


####Обновление данных:####
$data = array(
    'name' => 'Other name',
);
<br />
$this->some_model->update(2, $data);


####Получение списка записей:####
$this->some_model->getList();


