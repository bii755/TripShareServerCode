package serverotherclient;

import java.io.DataInputStream;
import java.net.Socket;

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
public class Receiver implements Runnable{

	Socket socket;
    DataInputStream in;
    String name;
    User user = new User();
 
    public Receiver(User user,Socket socket) throws Exception
    {
        this.user = user;
        this.socket = socket;
        //접속한 Client로부터 데이터를 읽어들이기 위한 DataInputStream 생성
        in = new DataInputStream(socket.getInputStream());
        //최초 사용자로부터 닉네임을 읽어들임
        this.name = in.readUTF();
        //사용자 추가해줍니다.
        user.AddClient(name, socket);
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
            		String total = in.readUTF();
            		String senderemail = in.readUTF();
            		String receiveremail = in.readUTF();
            		String rnum = in.readUTF();
            		String sendername = in.readUTF();	
            		String senderurl = in.readUTF();	
            		String ymd = in.readUTF();
            		String hm = in.readUTF();	
            		
            		if (type.equals("image")) {
						//이미지를 보낸 것이라면 byte 배열을 받는다.
            			System.out.println("---------기기에서 받은 데이터 체크 ------------");
            			String imgurl = in.readUTF();
            			int len =in.readInt();
            			System.out.println("이미지를 받았다. 길이가 "+len+"이다");
            			System.out.println("이미지 url은 "+imgurl+" 이다");
            			byte[] data = new byte[len];
            			
            			if (len>0) {
            				System.out.println("메세지 종류 "+type);
            				System.out.println("보낸 사람 이메일"+senderemail);
    	            		System.out.println("받을 사람들 이메일"+receiveremail);
    	            		System.out.println("보낸 사람 이름"+sendername);
    	            		System.out.println("보낸 사람 이미지"+senderurl);
    	            		System.out.println("나를 제외한 방의 총 인원 "+total);
    	            		System.out.println("방 번호"+rnum);
    	            		System.out.println("보낸 시간"+ymd+hm);
    	            		System.out.println("---------   끝   ------------");
							//이미지가 있을 때
            				in.readFully(data, 0, data.length);
            				if (total.equals("1")) {
    	            			//1:1채팅이고,모든 사용자에게 보냄
            					user.sendImage(type,data,receiveremail,  rnum, sendername, senderurl, ymd, hm, total,imgurl,receiveremail,senderemail);
    						}else {
    							//다중 채팅이다.
    							user.sendImagetoMany(type,data, receiveremail, rnum, sendername, senderurl, ymd, hm, total,imgurl,senderemail);
    						}	
            			}else {
            				System.out.println("이미지를 못 받았다.");
            			}
            			
					}else {
						//텍스트를 받는다.
						String msg = in.readUTF();	
						System.out.println("---------기기에서 받은 데이터 체크 ------------");
	            		System.out.println("메세지 종류 "+type);
	            		System.out.println("보낸 사람 이메일"+senderemail);
	            		System.out.println("받을 사람 이메일 "+receiveremail);
	            		System.out.println("보낸 사람 이름"+sendername);
	            		System.out.println("보낸 사람 이미지"+senderurl);
	            		System.out.println("나를 제외한 방의 총 인원 "+total);
	            		System.out.println("방 번호"+rnum);
	            		System.out.println("보낸 시간"+ymd+hm);
	            		System.out.println("메세지"+msg);
	            		System.out.println("---------   끝   ------------");
	            		if (total.equals("1")) {
	            			//1:1채팅이고, 다른 사용자에게 보냄
	                        user.sendMsg(type,msg ,receiveremail, rnum,sendername,senderurl,ymd,hm,total,receiveremail,senderemail);
	        
						}else {
							//다중 채팅이다.
							user.sendMsgtoMany(type,msg, receiveremail, rnum, sendername, senderurl, ymd, hm, total,senderemail);
						}
	       	
					}
      
            		
            		
            		
            		
            	     }
         
            }
        }catch(Exception e) {
            System.out.println("메세지 받는 중 에러"+e.getMessage());
            user.RemoveClient(this.name);
        }        
    }

}
