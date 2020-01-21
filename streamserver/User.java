package streamserver;

import java.io.DataOutputStream;
import java.net.Socket;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Set;

public class User {

/*
한 사용자의 정보를 더하거나, 제거하거나,
다른 사용자들에게 메세지를 보내는 기능을 한다.

synchronize를 사용해 한 사용자의 스레드가 작업들을(사용자 추가, 삭제, 메세지 보내기)
하는 동안에 각각의 작업을 다른 사용자의 스레드들이 할 수 없게 한다.
그래서 동기화 문제를 해결한다.
동기화 문제는 예를 들면 사용자가 5명에서 7명이 됬는다
기존 5명은 7명이 된 사실을 모르는 것이다.  
*/
	HashMap<String,DataOutputStream> clientmap  = new HashMap<String,DataOutputStream>(); 
	HashMap<String,String> roommap = new HashMap<String,String>();
	 
		String roomnum, roomname;
		int numsent;
	public synchronized void AddClient(String name,Socket socket,String roomid) 
		{                                                                        
			try {
				//sendMsg(name+" 입장하셨습니다.","Server");
				//사용자 이름을 키로 하고 값을  데이터를 보내는 객체로 한다.
				//소켓은 각각의 사용자 ip,포트 번호를 가진 사용자만의 소켓이므로 
				//사용자를 위한 출력장치를 값으로 가진 것이다.
				System.out.println("User addclient :user email " +name);
				clientmap.put(name, new DataOutputStream(socket.getOutputStream()));
				roommap.put(name, roomid);
				System.out.println("User 스트리밍 참여 총 인원 : "+clientmap.size());
				System.out.println(name+"은  "+roomid+"의 스트리밍 보고있다. ");
			}catch(Exception e){
				System.out.println("사용자 추가중에 에러"+e.getMessage());
			}
			
		}
	
		public synchronized void RemoveClient(String name)  
		{
		try {
				clientmap.remove(name);
				roommap.remove(name);
//				sendMsg(name + " 퇴장하셨습니다.", "Server", roomnum);
				System.out.println(name+"이 퇴장");
				System.out.println("User 스트리밍 전체 참여 인원 : "+clientmap.size());
			}catch(Exception e) {
				System.out.println("사용자 나가는 중에 에러"+e.getMessage());
			}
		}
	
		public synchronized void sendMsg(String type, String senderemail, String receiveremail, 
				String sendername, String senderurl, String message)throws Exception 
		{
	
			System.out.println(receiveremail+"에게 " +senderemail+"가" );
			System.out.println(message+"를 보낼것이다.");
			System.out.println("메세지 타입은 "+type);
			System.out.println("보내는 사람 이름은 "+sendername);
			System.out.println("보내는 사람 url은  "+senderurl);
			
			if (clientmap.get(receiveremail)==null) {
				System.out.println("소켓이 null이야");
			}else {
				numsent = numsent+1;
				System.out.println("소켓이 null이 아니야");
				clientmap.get(receiveremail).writeUTF(type);
				System.out.println("type "+type);
				clientmap.get(receiveremail).writeUTF(senderemail);
				System.out.println("senderemail "+senderemail);
				clientmap.get(receiveremail).writeUTF(sendername);
				System.out.println("sendername "+sendername);
				clientmap.get(receiveremail).writeUTF(senderurl);
				System.out.println("senderurl "+senderurl);
				clientmap.get(receiveremail).writeUTF(message);
				System.out.println("message "+message);
			
			}
		}
		
		public synchronized void sendMsgtoMany(String type, String senderemail, 
				String sendername, String senderurl, String message,String roomid) throws Exception 
		{
			
			System.out.println("보낼 email 갯수 " );
			numsent = 0;
			
			//클라이언트의 키 값을 구한다.
			Set  set = clientmap.keySet();
			//하나씩 가져오기 위한 준비
			Iterator iterator = set.iterator();
			
			//하나씩 가져온다.
			while(iterator.hasNext()){
				  String receiveremail = (String)iterator.next();
				  System.out.println("hashMap receiveremail : " + receiveremail);
				  String userroomid  = roommap.get(receiveremail);
				  //1. 보낸 사람을 제외하고,
				  //2. 받는 사람이 현재 있는 방이랑 메세지를 보낸 사람의 방이 같다면 
				  //메세지를 보낸다.
				  if (!senderemail.equals(receiveremail) && userroomid.equals(roomid)) {
					  sendMsg(type, senderemail , receiveremail, sendername,senderurl, message);
				}	
		  }
	
			System.out.println(numsent+"명에게 메세지 다보냈다. 끝-------- " );
			System.out.println("\n"+"\n"+"\n");
				
		}
}
