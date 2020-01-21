package streamserver;

import java.net.ServerSocket;
import java.net.Socket;

import streamserver.Receiver;
import streamserver.User;

public class Server {

	public static void main(String arg[])
    {
        //접속한 Client와 통신하기 위한 Socket
        Socket socket = null;    
        //채팅방에 접속해 있는 Client 관리 객체                
        User user = new User();        
        //Client 접속을 받기 위한 ServerSocket            
        ServerSocket server_socket=null;              
        
        //Client와 메세지를 주고받는  thread를 10개만 만들겠다는 것
        int count = 0;                            
        Thread thread[]= new Thread[50];             
        
        try {
            server_socket = new ServerSocket(9999);
         
            //Server의 메인쓰레드는 게속해서 사용자의 접속을 받음
            while(true)
            {
            	//사용자가 접속할 때 까지 대기함
            	//사용자의 접속을 받을 경우에만 다음 코드가 실행됨
            	//연결된 클라이언트의 ip,port정보를 가지고 있음
            	socket = server_socket.accept();
                System.out.println("Server socket연결됨 : "+socket.toString());
                
                //클라에게 메세지를 보내는 스레드를 
                thread[count] = new Thread(new Receiver(user,socket));
                thread[count].start();
                count++;
            }
        }catch(Exception e) {
        	System.out.println("서버 연결중 에러 "+e.getMessage());
        };
    }
}
