<?php
namespace app\modules\v1\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%guide}}".
 *
 * @property integer $id
 * @property string $imgurl
 * @property integer $status
 * @property integer $flag
 */
class Guide extends ActiveRecord implements IdentityInterface
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['id']);

        return $fields;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function tableName()
    {
        return '{{%user_base}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'imgurl',
                    'status',
                    'flag'
                ],
                'required'
            ],
            [
                [
                    'status',
                    'flag'
                ],
                'integer'
            ],
            [
                [
                    'imgurl'
                ],
                'string',
                'max' => 255
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'imgurl' => Yii::t('app', 'imgurl'),
            'status' => Yii::t('app', 'status'),
            'flag' => Yii::t('app', 'flag')
        ];
    }

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return ($user);
            }
        }

        return null;
    }
}