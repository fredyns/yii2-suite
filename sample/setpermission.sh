#!/bin/bash

sudo echo "change ownership as yours."
sudo chown -R $USER ..

echo "change group."
sudo chgrp -R www-data ..
sudo chmod -R 775 ..

echo "set runtime folder."
sudo chgrp -R www-data runtime
sudo chmod -R 777 runtime

echo "set web assets."
sudo chgrp -R www-data web/assets
sudo chmod -R 777 web/assets

echo "done."