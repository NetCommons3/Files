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

# 認証キーと組み合わせ使う方法

[認証キープラグイン](https://github.com/NetCommons3/AuthorizationKeys)と組み合わせることで特定の認証キーを知っているユーザだけがファイルダウンロード可能にできます。

## リダイレクト型

ダウンロードアクションを実装しているコントローラで AuthorizationKeyComponent を使います

```php
public $components = array(
    'Files.Download',
    'AuthorizationKeys.AuthorizationKey' => [
        'operationType' => 'redirect',
        'targetAction' => 'download_pdf',
        'model' => 'BlogEntry',
    ],
);
```

ダウンロードアクション内で認証キーによるガードを設定します

```php
public function download_pdf() {
    // ここから元コンテンツを取得する処理
    $this->_prepare();
    $key = $this->params['pass'][1];

    $conditions = $this->BlogEntry->getConditions(
            Current::read('Block.id'),
            $this->Auth->user('id'),
            $this->_getPermission(),
            $this->_getCurrentDateTime()
    );

    $conditions['BlogEntry.key'] = $key;
    $options = array(
            'conditions' => $conditions,
            'recursive' => 1,
    );
    $blogEntry = $this->BlogEntry->find('first', $options);
    // ここまで元コンテンツを取得する処理

    // 認証キーによるガード
    $this->AuthorizationKey->guard('redirect', 'BlogEntry', $blogEntry);

    // ダウンロード実行
    if ($blogEntry) {
        return $this->Download->doDownload($blogEntry['BlogEntry']['id'], ['filed' => 'pdf']);
    } else {
        // 表示できない記事へのアクセスなら404
        throw new NotFoundException(__('Invalid blog entry'));
    }
}

```


## ポップアップ型
ダウンロードアクションを実装しているコントローラで AuthorizationKeyComponent を使います

```php
public $components = array(
    'Files.Download',
    'AuthorizationKeys.AuthorizationKey' => [
        'operationType' => 'redirect',
        'targetAction' => 'download_pdf',
        'model' => 'BlogEntry',
    ],
);
```

ダウンロードアクション内でのガードをpopupにします

```php
    $this->AuthorizationKey->guard('popup', 'BlogEntry', $blogEntry);
```

ダウンロードリンクを書き換えてポップアップ画面が表示されるようにします。 認証キーポップアップはAngularJSのディレクティブ authorization-keys-popup-link として実装されています。

```php
<div>
    PDF :
    <?php echo $this->Html->link('PDF',
            '#',
        ['authorization-keys-popup-link',
            'url' => $this->NetCommonsHtml->url(
                [
                    'action' => 'download_pdf',
                    'key' => $blogEntry['BlogEntry']['key'],
                    'pdf',
                ]
            ),
            'frame-id' => Current::read('Frame.id')
        ]
    ); ?>
</div>

```

認証キーの詳細については認証キーのドキュメントも参照してください。

[AuthorizationKeys](https://github.com/NetCommons3/AuthorizationKeys#概要)

# Validation

AttachmentBehaviorでUploadビヘイビアのバリデーションルールを利用できます。

- [Uploadビヘイビアのバリデータ](https://github.com/josegonzalez/cakephp-upload/blob/2.x/docs/validation.rst)