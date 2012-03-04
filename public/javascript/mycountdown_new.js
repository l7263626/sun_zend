// JavaScript Document
function countdown(target,allSeconds,_self){          
    this.second=0;  //指定秒數
    this.minute=0; //指定分數
    this.hour=0; //指定幾時
    this.day=0;//指定幾天
    this.month=0;//指定幾月
    this.year=0;//指定幾年
    this.tmpSec=allSeconds;
    this.target=target;
    this.sid;
    this.isrunning=0;

    this.output=function(){
        this.target.innerHTML=this.year+"年"+this.month+"月"+this.day+"日"+this.hour+"時"+this.minute+"分"+this.second+"秒";              
    }
    this.count=function(){                                
        this.tmpSec--;  //總秒數遞減
        var tmp=this.parseSec(this.tmpSec,60); //算出幾秒
        this.second=tmp[1];  //指定秒數
        tmp=this.parseSec(tmp[0],60); //算出幾分
        this.minute=tmp[1]; //指定分數
        tmp=this.parseSec(tmp[0],24); //算出幾時
        this.hour=tmp[1]; //指定幾時
        tmp=this.parseSec(tmp[0],30);//算出幾天
        this.day=tmp[1];//指定幾天
        tmp=this.parseSec(tmp[0],12);//算出幾月
        this.month=tmp[1];//指定幾月
        this.year=tmp[0];//指定幾年
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