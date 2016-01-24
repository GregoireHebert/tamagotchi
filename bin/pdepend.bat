@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/pdepend/pdepend/src/bin/pdepend
php "%BIN_TARGET%" %*
