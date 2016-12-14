#!/bin/bash

sudo chgrp -R www-data ..
sudo chmod -R 775 ..

sudo chgrp -R www-data runtime
sudo chmod -R 777 runtime

sudo chgrp -R www-data web/assets
sudo chmod -R 777 web/assets

