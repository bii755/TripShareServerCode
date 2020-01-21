package serverotherclient;

import java.io.DataOutputStream;
import java.net.Socket;
import java.util.HashMap;
import java.util.Iterator;


/*
한 사용자의 정보를 더하거나, 제거하거나,
다른 사용자들에게 메세지를 보내는 기능을 한다.

synchronize를 사용해 한 사용자의 스레드가 작업들을(사용자 추가, 삭제, 메세지 보내기)
하는 동안에 각각의 작업을 다른 사용자의 스레드들이 할 수 없게 한다.
그래서 동기화 문제를 해결한다.
동기화 문제는 예를 들면 사용자가 5명에서 7명이 됬는다
기존 5명은 7명이 된 사실을 모르는 것이다.  
*/
public class User {

		HashMap<String,DataOutputStream> clientmap 
		     = new HashMap<String,DataOutputStream>(); 
		String roomnum;
		int numsent;
	public synchronized void AddClient(String name,Socket socket) 
		{                                                                        
			try {
				//sendMsg(name+" 입장하셨습니다.","Server");
				//사용자 이름을 키로 하고 값을  데이터를 보내는 객체로 한다.
				//소켓은 각각의 사용자 ip,포트 번호를 가진 사용자만의 소켓이므로 
				//사용자를 위한 출력장치를 값으로 가진 것이다.
				System.out.println("User addclient :user email " +name);
				clientmap.put(name, new DataOutputStream(socket.getOutputStream()));
				System.out.println("User 채팅 참여 인원 : "+clientmap.size());
			}catch(Exception e){
				System.out.println("사용자 추가중에 에러"+e.getMessage());
			}
			
		}
		public synchronized void RemoveClient(String name)  
		{
		try {
				clientmap.remove(name);
//				sendMsg(name + " 퇴장하셨습니다.", "Server", roomnum);
				System.out.println(roomnum+"방"+name+"퇴장");
				System.out.println("User 채팅 참여 인원 : "+clientmap.size());
			}catch(Exception e) {
				System.out.println("사용자 나가는 중에 에러"+e.getMessage());
			}
		}
		
		public synchronized void sendMsg(String type,String msg, String receiveremail, String rnum, 
				String sendername, String senderurl, String ymd, String hm,String total,String oneemail,String senderemail)throws Exception 
		{
		/*
		 * //사용자 이름을 순차적으로 가져온다 Iterator iterator = clientmap.keySet().iterator();
		 * while(iterator.hasNext()) { String clientname =(String)iterator.next();
		 * 
		 * }
		 */
			System.out.println(receiveremail+"중에서 "+oneemail+"에게" +senderemail+"가" );
			System.out.println(msg+"를 보낼것이다.");
			System.out.println("방번호는 "+rnum);
			if (clientmap.get(oneemail)==null) {
				System.out.println("소켓이 null이야");
			
			}else {
				numsent = numsent+1;
				System.out.println("소켓이 null이 아니야");
				clientmap.get(oneemail).writeUTF(type);
				System.out.println("type"+type);
				clientmap.get(oneemail).writeUTF(senderemail);
				System.out.println("senderemail "+senderemail);
				clientmap.get(oneemail).writeUTF(receiveremail);
				System.out.println("receiveremail "+receiveremail);
				clientmap.get(oneemail).writeUTF(rnum);
				System.out.println("rnum"+rnum);
				clientmap.get(oneemail).writeUTF(sendername);
				System.out.println("sendername"+sendername);
				clientmap.get(oneemail).writeUTF(senderurl);
				System.out.println("senderurl"+senderurl);
				clientmap.get(oneemail).writeUTF(ymd);
				System.out.println("ymd"+ymd);
				clientmap.get(oneemail).writeUTF(hm);
				System.out.println("hm"+hm);
				clientmap.get(oneemail).writeUTF(total);
				System.out.println("total"+total);
				clientmap.get(oneemail).writeUTF(msg);
				System.out.println("msg"+msg);
				System.out.println("성공적으로 메세지를 보냄.");
				roomnum = rnum;
			}
			//한 명일 경우
			if (total.equals("1")) {
				System.out.println("한 명에게만 보냄");
			}
			
		}
		public synchronized void sendImage(String type,byte[] imagedata, String receiveremail, String rnum, 
				String sendername, String senderurl, String ymd, String hm,String total, String imgurl,String oneemail,String senderemail)throws Exception 
		{
		/*
		 * //사용자 이름을 순차적으로 가져온다 Iterator iterator = clientmap.keySet().iterator();
		 * while(iterator.hasNext()) { String clientname =(String)iterator.next();
		 * 
		 * }
		 */
			System.out.println(receiveremail+"중에서 "+ oneemail+"에게" );
			System.out.println(imagedata.length+" 길이인 이미지를 보낼것이다.");
			System.out.println("방번호는 "+rnum);
			if (clientmap.get(oneemail)==null) {
				System.out.println("소켓이 null이야");
			
			}else {
				//보낸 사람 1명 추가
				numsent = numsent +1;
				System.out.println("소켓이 null이 아니야");
				clientmap.get(oneemail).writeUTF(type);
				System.out.println("type"+type);
				clientmap.get(oneemail).writeUTF(senderemail);
				System.out.println("senderemail :"+senderemail);
				clientmap.get(oneemail).writeUTF(receiveremail);
				System.out.println("receiveremail: "+receiveremail);
				clientmap.get(oneemail).writeUTF(rnum);
				System.out.println("rnum"+rnum);
				clientmap.get(oneemail).writeUTF(sendername);
				System.out.println("sendername"+sendername);
				clientmap.get(oneemail).writeUTF(senderurl);
				System.out.println("senderurl"+senderurl);
				clientmap.get(oneemail).writeUTF(ymd);
				System.out.println("ymd"+ymd);
				clientmap.get(oneemail).writeUTF(hm);
				System.out.println("hm"+hm);
				clientmap.get(oneemail).writeUTF(total);
				System.out.println("total"+total);
			
			  clientmap.get(oneemail).writeUTF(imgurl);
			  System.out.println("receiveremail"+imgurl);
			 
				roomnum = rnum;
				
				clientmap.get(oneemail).writeInt(imagedata.length);
				System.out.println("lenth"+imagedata.length);
				clientmap.get(oneemail).write(imagedata,0, imagedata.length);
				System.out.println("성공적으로 이미지를 보냄.");
			}
			//한 명일 경우
			if (total.equals("1")) {
				System.out.println("한 명에게만 보냄");
			}
			
		}
		public synchronized void sendImagetoMany(String type, byte[] image , String receiveremail,  String rnum, 
				String sendername, String senderurl, String ymd, String hm, String total,String imgurl,String senderemail)throws Exception 
		{
			
			String[] emails = receiveremail.split(",");
			numsent = 0;
			for (int i = 0; i < emails.length; i++) {
				String oneemail = emails[i];
				sendImage(type, image , receiveremail , rnum,sendername,senderurl,ymd,hm,total,imgurl, oneemail,senderemail);
			}		
			System.out.println(emails.length+"명 중 "+numsent+"명에게 이미지 다보냈다." );
				
		}
		public synchronized void sendMsgtoMany(String type, String msg, String receiveremail,  String rnum, 
				String sendername, String senderurl, String ymd, String hm, String total,String senderemail)throws Exception 
		{
			
			String[] emails = receiveremail.split(",");
			System.out.println("보낼 email 갯수 " +emails.length);
			numsent = 0;
			for (int i = 0; i < emails.length; i++) {
				String oneemail = emails[i];
				System.out.println("다음차례");
				sendMsg(type, msg , receiveremail, rnum,sendername,senderurl,ymd,hm,total,oneemail,senderemail);
			}		
			System.out.println(numsent+"명에게 메세지 다보냈다. 끝-------- " );
			System.out.println("\n"+"\n"+"\n");
				
		}
}
