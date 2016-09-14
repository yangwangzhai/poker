/**
 * Created by Administrator on 2016/3/30.
 */
var mysql=require("mysql");
var pool = mysql.createPool({
    host: 'localhost',
    user: 'user',
    password: 'password',
    database: 'database',
    port: port
});

var query=function(sql,callback){
    pool.getConnection(function(err,conn){
        if(err){
            callback(err,null,null);
        }else{
            conn.query(sql,function(qerr,vals,fields){
                //释放连接
                conn.release();
                //事件驱动回调
                callback(qerr,vals,fields);
            });
        }
    });
};

module.exports=query;
//use
/*var query=require("./lib/mysql.js");

query("select 1 from 1",function(err,vals,fields){
    //do something
});  */
