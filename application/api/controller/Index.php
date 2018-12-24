<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use PHPMailer\PHPMailer\PHPMailer;
use think\Session;
use think\View;
header("Access-Control-Allow-Origin: *");
class Index extends Controller
{
    //  用来直接检查接口安全
    public function key()
   {
        $key = file_get_contents('key.txt');
        if ($key==input('key')){
            return 1;
        }else{
            return 0;
        }
   }
    /*
     * 注册接口
     */
    public function register()
    {
            if ($this->key()==1){

                //检测邮箱是否注册过
                $email = model('login')->where(['email'=>input('email')])->find();
                if ($email){
                    return [
                        'status'=>103,
                        'message'=>'邮箱已被注册',
                    ];
                }
                //检测手机号是否注册过
                $phone = model('login')->where(['telphone'=>input('telphone')])->find();
                if ($phone){
                    return [
                        'status'=>104,
                        'message'=>'手机号已被注册',
                    ];
                }
                $model = model('login');
                $model->data([
                        'telphone'=>input('telphone'),
                        'email'=>input('email'),
                        'pwd'=>sha1(md5(input('pwd'))),
                    ]);
                $model->save();
                Session::set('userid',$model->id);
                if ($model){
                    $this->sendMail(input('email'),'邮箱验证',file_get_contents('email.html'));
                    return [
                        'status'=>100,
                        'message'=>'注册成功，请完成邮箱验证',
                        'id'=>$model->id,
                    ];
                }else{
                    return [
                        'status'=>102,
                        'message'=>'注册失败',
                    ];
                }
            }else{
                return [
                    'status'=>101,
                    'message'=>'密钥不正确',
                ];
            }
        }

    /**
     * 邮箱绑定
     */
    public function emailband()
    {
            if ($this->key()==1){
                $model = model('login')->save(['email_status'=>1],['id'=>Session::get('userid')]);
                if ($model){
                    Session::delete('userid');
                    return [
                        'status'=>100,
                        'message'=>'邮箱绑定成功',
                    ];
                }else{
                    return [
                        'status'=>102,
                        'message'=>'邮箱绑定失败',
                    ];
                }
            }else{
                return [
                    'status'=>101,
                    'message'=>'密钥不正确',
                ];
            }
        }
    /**
     * 登录接口
     */
    public function login()
    {
        if ($this->key()==1){
            //读取该用户有关的信息
            $model = model('login')->where(['telphone'=>input('user')])->find()->toArray();
            if ($model){
                //判断密码是否正确
                if ($model['pwd']==sha1(md5(input('pwd')))){
                    return [
                        'status'=>100,
                        'message'=>'密钥正确,登录成功',
                        'data'=>$model
                    ];
                }else{
                    return [
                        'status'=>102,
                        'message'=>'密码不正确',
                        'data'=>''
                    ];
                }
            }
        }else{
            return [
                'status'=>101,
                'message'=>'密钥不正确',
            ];
        }
    }
    /**
     * 发送邮箱的控制器
     */
    /**
    2  * 发送邮件方法
    3  * @param string $to：接收者邮箱地址
    4  * @param string $title：邮件的标题
    5  * @param string $content：邮件内容
    6  * @return boolean  true:发送成功 false:发送失败
    7  */
 function sendMail($to,$title,$content){


     //实例化PHPMailer核心类
     $mail = new PHPMailer();
     //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
     $mail->SMTPDebug = 1;
     //使用smtp鉴权方式发送邮件
     $mail->isSMTP();
     //smtp需要鉴权 这个必须是true
     $mail->SMTPAuth=true;
     //链接qq域名邮箱的服务器地址
     $mail->Host = 'smtp.qq.com';
     //设置使用ssl加密方式登录鉴权
     $mail->SMTPSecure = 'ssl';
     //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
     $mail->Port = 465;
     //设置smtp的helo消息头 这个可有可无 内容任意
     $mail->Helo = '';
     //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
     $mail->Hostname = 'localhost';
     //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
     $mail->CharSet = 'UTF-8';
     //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
     $mail->FromName = '直播平台';
     //smtp登录的账号 这里填入字符串格式的qq号即可
     $mail->Username ='1414210968';
     //smtp登录的密码 使用生成的授权码 你的最新的授权码
     $mail->Password = 'ynuxezybpbwfghec';
     //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
     $mail->From = '1414210968@qq.com';
     //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
     $mail->isHTML(true);
     //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
     $mail->addAddress($to,'测试通知');
     //添加多个收件人 则多次调用方法即可
     // $mail->addAddress('xxx@qq.com','lsgo在线通知');
     //添加该邮件的主题
     $mail->Subject = $title;
     //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
     $mail->Body = $content;

     //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
     // $mail->addAttachment('./d.jpg','mm.jpg');
     //同样该方法可以多次调用 上传多个附件
     // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

     $status = $mail->send();

    }

    /**
     * 找回密码
     */
    public function findpwd()
    {
        if ($this->key()==1){
            $model = model('login');
            //接收取到的值
            $res = $model->save(['pwd'=>sha1(md5(input('pwd')))],['email|telphone'=>input('user')]);
            if ($res){
                return [
                    'status'=>100,
                    'message'=>'修改密码成功，重新登录',
                ];
            }else{
                return [
                    'status'=>102,
                    'message'=>'密码修改失败',
                    'data'=>''
                ];
            }
        }else{
            return [
                'status'=>101,
                'message'=>'密钥不正确',
            ];
        }
    }

    /**
     * 明星大神的接口
     */
    public function starmanito()
    {
        if ($this->key()==1){
            //根据粉丝量排序筛选数据
            $model = model('login');
            $data = $model->order('fans','desc')->field('id,username,photo,person')->select();
            $info = $data->toArray();
            if ($data){
                return[
                    'data'=>$info,
                    'status'=>100,
                    'message'=>'数据请求成功',
                ];
            }else{
                return[
                    'data'=>'',
                    'status'=>102,
                    'message'=>'数据请求失败',
                ];
            }
        }else{
            return [
                'status'=>101,
                'message'=>'密钥不正确',
            ];
        }
    }
}
