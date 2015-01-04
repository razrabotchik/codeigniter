# Библиотека CodeIgniter для работы с базой данных #

### Пример использования ###

Пример использования в файле some_model.php

####Добавление записи в базу данных:####

    $data = array(
        'name' => 'Some name'
    );
    $this->some_model->create($data);

####Удаление записи из базы данных:####
    $this->some_model->delete(2);


####Обновление данных:####
    $data = array(
        'name' => 'Other name',
    );
    $this->some_model->update(2, $data);

####Получение списка записей:####
    $this->some_model->getList();


Отслеживать за новыми наработками можно в [моем блоге][http://golubovsky.name/].