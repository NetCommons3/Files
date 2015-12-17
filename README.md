Files
==============

Files for NetComomns3

[![Build Status](https://api.travis-ci.org/NetCommons3/Files.png?branch=master)](https://travis-ci.org/NetCommons3/Files)
[![Coverage Status](https://coveralls.io/repos/NetCommons3/Files/badge.png?branch=master)](https://coveralls.io/r/NetCommons3/Files?branch=master)

| dependencies  | status |
| ------------- | ------ |
| composer.json | [![Dependency Status](https://www.versioneye.com/user/projects/54e54d12b3ca9bffb4000185/badge.png)](https://www.versioneye.com/user/projects/54e54d12b3ca9bffb4000185) |


# AttachmentBehavior

ファイルアップロードをするフォームを表示するときはModel::recursiveを0以上にしてください。

Model::recursive = -1の場合、Modelに添付されたアップロードファイルの情報が取得できないため、NetCommonsForm::uploadFile()は添付されたファイルが無いと判断してしまいます。

