package streamserver;

import java.io.DataInputStream;
import java.net.Socket;


public class Receiver implements Runnable{

/*
 역할 :
1.메세지 전달 
사용자에게 받은 메세지를
다른 사용자에게 보내는 서브 스레드
2.사용자 추가 
메인스레드에서 생성되자마자 
사용자의 이름을 서버의 채팅 참여자 목록에 추가한다.
3. 소켓 생성 
클라이언트들의 메세지를 받을 소켓을 클라이언트 별로 생성함
4. 사용자 삭제
클라이언트가 접속을 끝는다면

생성 시기 : 사용자가 서버와 연결에 성공하면 메인 스레드에서 생성한다.

*/

	Socket socket;
    DataInputStream in;
    String name, roomid;
//    String streamroomnum;
    User user = new User();
 
    public Receiver(User user,Socket socket) throws Exception
    {
        this.user = user;
        this.socket = socket;
        //접속한 Client로부터 데이터를 읽어들이기 위한 DataInputStream 생성
        in = new DataInputStream(socket.getInputStream());
        //최초 사용자로부터 이메일, 방아이디 읽음
        this.name = in.readUTF();
        this.roomid= in.readUTF();
      //사용자 추가해줍니다.
        user.AddClient(name, socket, roomid);
       
        /*
		 * 최초 사용자에게 참가한 방번호도 읽음 // this.streamroomnum = in.readUTF(); //
		 */   
        
        System.out.println(name+"사용자 추가");
    }
	
    public void run()
    {
        try
        {   
            while(true)
            {   	
            	if (in.available()>0) {
            		String type = in.readUTF();
            		String senderemail = in.readUTF();
            		String sendername = in.readUTF();
            		String senderurl = in.readUTF();
            		String message = in.readUTF();
            		String roomid = in.readUTF();

					          			            		            			
            		System.out.println("메세지 종류 "+type);
            		System.out.println("보낸 사람 이메일"+senderemail);
            		System.out.println("보낸 사람 이름"+sendername);
            		System.out.println("보낸 사람 url"+senderurl);
    	            System.out.println("보낸 사람 메세지"+message);
    	            System.out.println("보낸 사람 방 이름 "+roomid);
    	            System.out.println("---------   끝   ------------");
			
					if (type.equals("exit")) {
						//사용자가 방을 나가면 소켓이랑 방 번호를 지운다
						user.RemoveClient(senderemail);
					}else {
						user.sendMsgtoMany(type,senderemail, sendername, senderurl,message,roomid);	
					}
    	            
	       	
					}
            }
        }catch(Exception e) {
            System.out.println("메세지 받는 중 에러"+e.getMessage());
            user.RemoveClient(this.name);
        }        
    }
}
