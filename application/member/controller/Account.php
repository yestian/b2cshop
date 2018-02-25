<?php
namespace app\member\controller;
use Aliyun\DySDKLite\SignatureHelper;
use phpmailer\phpmailer\PHPMailer;

class Account extends Base{

    public function reg(){
        if(request()->isPost()){
            $data=input('post.');
            $data['checked']=1;//验证
            $data['password']=md5($data['password']);
            //邮箱注册类型
            if($data['register_type']==0){
                //判断邮箱地址和验证码是否一致
                if(!($data['email']==session('email') && $data['send_code']==session('emailcode'))){
                    $this->error('邮箱地址和验证码不对应！');
                }
                $data['email_checked']=1; 
            }

             //手机注册类型
            if($data['register_type']==1){
                 //判断手机号码和验证码是否一致
                if(!($data['mobile_phone']==session('mobile_phone') && $data['send_code']==session('mobile_code'))){
                    $this->error('手机号码和验证码不对应！');
                }
                $data['phone_checked']=1;
            }
            
            
            $res=db('user')->strict(false)->insert($data);
            if($res){
                //清除注册时候的session
                session('email',null);
                session('emailcode',null);
                session('mobile_code',null);
                session('mobile_phone',null);
                $this->success('注册成功！','login');
            }else{
                $this->error('注册失败！');
            }
        }
        return view();
    }

    public function login(){
        return view();
    }

//验证----------------------------------------------------
    //异步验证注册用户名是否存在
    public function checkusername(){
        if(request()->isAjax()){
            $username=input('username');
            $res=db('user')->where('username',$username)->find();
            if($res){
                echo 'false';//用户名已经存在
            }else{
                echo 'true';//可以注册
            }
            
        }
    }
    //异步验证注册手机号码是否存在
    public function checkPhone(){
        if(request()->isAjax()){
            $mobile_phone=input('mobile_phone');
            $res=db('user')->where('mobile_phone',$mobile_phone)->find();
            if($res){
                echo 'false';//手机号已经存在
            }else{
                echo 'true';//可以注册
            }
            
        }
    }
     //异步验证注册邮箱是否存在
     public function checkemail(){
        if(request()->isAjax()){
            $email=input('email');
            $res=db('user')->where('email',$email)->find();
            if($res){
                echo 'false';//用户名已经存在
            }else{
                echo 'true';//可以注册
            }
            
        }
    }

    /**
     * $to:发送到哪个邮箱
     * $title:发送的标题
     * $content:发送的内容
     */
    function sendMail(){
        if(request()->isAjax()){
            $data=input('post.');
            $config=model('conf')->getConf();//获取配置信息

            $to=$data['email'];
            $title='商城注册邮箱验证码';
            $num=rand(100000,999999);//生成随机数
            session('emailcode',$num);//存储验证码
            session('email',$to);//保存邮箱，在提交注册时候比对验证
            $content='注册验证码为：'.$num;
            
            $mail = new \PHPMailer();
            
            // 设置为要发邮件
            $mail->IsSMTP();
            // 是否允许发送HTML代码做为邮件的内容
            $mail->IsHTML(TRUE);
            $mail->CharSet='UTF-8';
            // 是否需要身份验证
            $mail->SMTPAuth=TRUE;
            /*  邮件服务器上的账号是什么 -> 到163注册一个账号即可 */
            $mail->From=$config['email_addr'];
            $mail->FromName=$config['email_sender_name'];
            $mail->Host=$config['email_smtp'];  //发送邮件的服务协议地址
            $mail->Username=$config['email_name'];
            $mail->Password=$config['email_pwd'];
            // 发邮件端口号默认25
            $mail->Port = 25;
            // 收件人
            $mail->AddAddress($to);
            // 邮件标题
            $mail->Subject=$title;
            // 邮件内容
            $mail->Body=$content;

            return $mail->send();
        }
        
    }

    //验证邮箱验证码
    public function check_sendemailcode(){
        if(request()->isAjax()){
            $data=input('send_code');
            if($data==session('emailcode')){
                echo 'true';
            }else{
                echo 'false';
            }
        }
    }


    //发送手机验证码
    public function sendSms(){
        if(request()->isAjax()){
            $config=model('conf')->getConf();//获取配置信息
            $data=input('mobile_phone');
            
            $params = array ();
            // *** 需用户填写部分 ***
            //定义下面使用的参数变量
            $phonecode=rand(100000,999999);//短信码
            $signName=$config['sign_name'];//签名，在阿里平台定义，不能在这里修改字符
            $TemplateCode=$config['TemplateCode'];//短信模板编号
            //存进session
            session('mobile_phone',$data);
            session('mobile_code',$phonecode);
            //秘钥
            $accessKeyId=$config['accessKeyId'];
            $accessKeySecret=$config['accessKeySecret'];

            // fixme 必填: 短信接收号码
            $params["PhoneNumbers"] = $data;
            // fixme 必填: 短信签名
            $params["SignName"] = $signName;
            // fixme 必填: 短信模板Code
            $params["TemplateCode"] = $TemplateCode;
            // fixme 可选: 设置模板参数
            $params['TemplateParam'] = Array (
                "code" => $phonecode,
                "product" => "阿里通信"
            );
            // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
            if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
                $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
            }
            // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
            $helper = new SignatureHelper();
            // 此处可能会抛出异常，注意catch
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
            );

            return $content;
            // 验证发送短信(SendSms)接口
            // print_r(sendSms());
        }
    }

    //验证手机验证码
    public function check_sendSms(){
        if(request()->isAjax()){
            $data=input('mobile_code');
            if($data==session('phonecode')){
                echo 'true';
            }else{
                echo 'false';
            }
        }
    }


}

