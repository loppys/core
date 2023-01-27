<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form data-toggle="validator" role="form" method="post">
                <label class="control-label h4">Настройка проекта</label>
                <hr>
                <div class="form-group">
                    <label for="inputName" class="control-label">Название проекта</label>
                    <input type="text" class="form-control" id="inputName" name="project"
                           placeholder="Введите название проекта" required>
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">
                        UUID (<a href="https://vengine.ru/auth/" target="_blank">Регистрация</a>)
                    </label>
                    <input type="text" class="form-control" id="inputName" name="uuid"
                           placeholder="Введите UUID выданный при регистрации" required>
                    <span style="color: rgba(255,5,0,0.64)" class="help-block">
                        без UUID функционал будет сильно ограничен
                    </span>
                </div>
                <hr>
                <div class="form-group">
                    <label for="inputKey" class="control-label">
                        Лицензионный ключ (<a href="https://vengine.ru/shop/subscribe/engine/" target="_blank">В магазин</a>)
                    </label>
                    <input type="text" class="form-control" id="inputKey" name="key"
                           maxlength="29"
                           minlength="29" 
                           placeholder="Введите купленный ключ">
                    <span style="color: rgba(255,5,0,0.64)" class="help-block">
                        Без ключа некоторые функции будут недоступны
                    </span>
                </div>
                <div class="form-group">
                    <label for="inputServiceToken" class="control-label">Токен от сервисов (<a href="https://vengine.ru/shop/services/" target="_blank">В магазин</a>)</label>
                    <input type="password" data-toggle="validator" maxlength="32" class="form-control"
                           id="inputServiceToken" name="token" placeholder="Введите токен">
                    <span class="help-block">Предоставляется при покупке доступа</span>
                </div>
                <hr>
                <div class="form-group">
                    <label for="inputRootPassword" class="control-label">Пароль root пользователя</label>
                    <input type="password" data-toggle="validator" maxlength="32" class="form-control"
                           id="inputRootPassword" name="root" placeholder="Пароль!" required>
                    <span class="help-block">Максимум 32 символа</span>
                </div>
                <hr>
                <div class="form-group">
                    <label for="inputPassword" class="control-label h5">Настройка базы данных</label>
                    <br>
                    <label for="inputDBType" class="control-label">
                        Тип базы данных (mysql - стандартное значение)
                    </label>
                    <input type="text" class="form-control" id="inputDBType" name="dbType"
                           placeholder="Тип базы данных" value="mysql">
                    <br>
                    <label for="inputDBHost" class="control-label">
                        Хост (localhost - стандартное значение)
                    </label>
                    <input type="text" class="form-control" id="inputDBHost" name="dbHost"
                           placeholder="localhost, 0.0.0.0" value="localhost">
                    <br>
                    <label for="inputDBName" class="control-label">
                        Наименование базы данных
                    </label>
                    <input type="text" class="form-control" id="inputDBName" name="dbName"
                           placeholder="Название созданной базы данных">
                    <br>
                    <label for="inputDBLogin" class="control-label">
                        Логин (root - стандартное значение)
                    </label>
                    <input type="text" class="form-control" id="inputDBLogin" name="dbLogin"
                           placeholder="Логин от учетной записи с доступом к нужной бд" value="root">
                    <br>
                    <label for="inputDBPassword" class="control-label">Пароль</label>
                    <input type="password" class="form-control" id="inputDBPassword" name="dbPassword"
                           placeholder="123456">
                </div>
                <hr>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="crypt" checked> Зашифровать конфиги
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Установить vEngine</button>
                </div>
            </form>
        </div>
    </div>
</div>

