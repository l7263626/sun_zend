// JavaScript Document
function countdown(target,allSeconds,_self){          
    this.second=0;  //���w���
    this.minute=0; //���w����
    this.hour=0; //���w�X��
    this.day=0;//���w�X��
    this.month=0;//���w�X��
    this.year=0;//���w�X�~
    this.tmpSec=allSeconds;
    this.target=target;
    this.sid;
    this.isrunning=0;

    this.output=function(){
        this.target.innerHTML=this.year+"�~"+this.month+"��"+this.day+"��"+this.hour+"��"+this.minute+"��"+this.second+"��";              
    }
    this.count=function(){                                
        this.tmpSec--;  //�`��ƻ���
        var tmp=this.parseSec(this.tmpSec,60); //��X�X��
        this.second=tmp[1];  //���w���
        tmp=this.parseSec(tmp[0],60); //��X�X��
        this.minute=tmp[1]; //���w����
        tmp=this.parseSec(tmp[0],24); //��X�X��
        this.hour=tmp[1]; //���w�X��
        tmp=this.parseSec(tmp[0],30);//��X�X��
        this.day=tmp[1];//���w�X��
        tmp=this.parseSec(tmp[0],12);//��X�X��
        this.month=tmp[1];//���w�X��
        this.year=tmp[0];//���w�X�~
        this.output();              
    }
    this.parseSec=function(num,s){
        var tmp=[0,0];          
        tmp[0]=Math.floor(num/s);
        tmp[1]=num%s;
        return tmp;
    }
    this.clearInterval=function(){
        clearInterval(this.sid);
        this.isrunning=0;
    }
    this.setInterval=function(){
        if(this.isrunning==0){
            this.sid=setInterval(_self+'.count()',1000);
            this.isrunning=1;
        }                      
    }
    this.setInterval();                        
}