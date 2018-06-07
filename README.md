# primabase
# Fungsi
* Manajemen pos
* Lihat daftar pos
* Kesehatan pos
* Alert

# Data
* Pos `{nama*, lonlat, desa, kec, kab, pengamat}`
* Logger `{sn*, pos_id}` 
* SettingLogger `{loggerid*, tinggisonar, tippingfactor, wlpressureoffset, wlpressurefactor, battcorrection}`
* User `{username*, password*, fullname}`
* Pengamat `{nama*, pos_id, alamat, desa, kec, kab, noktp}`
* Foto `{obj_type*, obj_id*, file*, taken, keterangan}`
~~* Tenant {name, addr}~~
* Anomali `{sampling, pos_id, logger_id, data}`
* Periodic `{pos_id, logger_id*, sampling*, wlevel, rain, temperature, humidity, altitude, battery, signalquality}`

## Data Relation
* `Logger (1) -- (1) SettingLogger`
* `Logger (1) -- (~) Periodic`
* `Pengamat (~) -- (1) Pos`

# Use Case
* Login/logout, menu berbeda user group Tenant dan admin
~~* CRUD Tenant~~
* CRUD Logger
* CRUD Pos
* View Periodic

# URLs
* `/login [GET, POST]`
* `/logout [GET]`
* `/logger [GET]` -> Tmapil daftar logger
  * `/{sn} [GET]` -> Detil logger dan dari table SettingLogger, dan periodic
  * `/{sn}/edit [GET, POST]` -> fungsi edit logger, yang diedit lebih utama SettingLogger