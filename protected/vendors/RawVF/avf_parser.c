/*************************************
 avf2rawvf version 0.4
 produces rawvf revision 3 from avf
 writes only mouse events
*************************************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#define VERSION 0.4

#define MAXREP 100000
#define MAXNAME 1000

struct event
{
	int sec,hun,ths;	//seconds,hundredths,thousandths
	int x,y;
	int mouse;
};
typedef struct event event;

FILE* AVF;

int ver,l,ll,ls,mode,w,h,m;
int size;
int* board;
int* board_state;
int qm;
int fs;
int bbbv;
int score_sec,score_ths;
char name[MAXNAME];
char skin[MAXNAME];
char program[MAXNAME];
char value[MAXNAME];
char timestamp[MAXNAME];
event video[MAXREP];

void pause()
{
}

char _fgetc(FILE* f)
{
	if(!feof(f)) return fgetc(f); else
	{
		printf("Unexpected end of file\n");
		exit(1);
	}
}

void getpair(FILE* f,char* c1,char* c2)
{
	int i=0;
	char c=0;
	
	while(c!=':' && c!=13 && i<MAXNAME)
	{
		c=_fgetc(f);
		if(c=='<')	// looks like arb's name<mouse>mouse pattern
		{
			c1[i]=c2[0]=0;
			while(_fgetc(f)!=13);
			return;
		}
		c1[i++]=c;
	}
	c1[--i]=0;i=0;
	
	while(c!=13 && i<MAXNAME)
	{
		c=_fgetc(f);
		c2[i++]=c;
	}
	c2[i]=0;
}

int readavf()
{
	int i,cur=0;
	unsigned char c,d;
	unsigned char cr[8];
	
	c=_fgetc(AVF);					// version
	fs=!(ver=c);
	
	if(ver)
		for(i=0;i<4;++i) c=_fgetc(AVF);	// meaningless bytes
	else
	{
		c=_fgetc(AVF);ver=c;
		c=_fgetc(AVF);l=c&0x1;ll=c&0x8;ls=c&0x10;
		c=_fgetc(AVF);
		c=_fgetc(AVF);
	}
	
	
	c=_fgetc(AVF);					// mode
	mode=c-2;
	if(mode==1)	// beg
	{
		w=h=8;m=10;
	}
	else if(mode==2) // int
	{
		w=h=16;m=40;
	}
	else if(mode==3) // exp
	{
		w=30;h=16;m=99;
	}
	else if(mode==4) // cus
	{
		w=(c=_fgetc(AVF))+1;
		h=(c=_fgetc(AVF))+1;
		m=(c=_fgetc(AVF));
		m=m*256+(c=_fgetc(AVF));
	}
	else return 0;

	board=(int*)malloc(sizeof(int)*w*h);
	for(i=0;i<w*h;++i) board[i]=0;
	for(i=0;i<m;++i)
	{
		c=_fgetc(AVF)-1;
		d=_fgetc(AVF)-1;
		board[c*w+d]=1;
	}
	
	// question marks | length of timestamp | [timestamp]
	for(i=0;i<7;++i) cr[i]=0;
	while(1)
	{
		while(cr[3]!='[') // search for timestamp
		{
			cr[0]=cr[1];cr[1]=cr[2];cr[2]=cr[3];cr[3]=_fgetc(AVF);
		}
		cr[0]=cr[1];cr[1]=cr[2];cr[2]=cr[3];cr[3]=_fgetc(AVF);
		if(cr[3]-47==mode) break;
	}
	if(cr[0]!=17 && cr[0]!=127) return 0;
	qm=(cr[0]==17);
	_fgetc(AVF);
	i=0;while(i<MAXNAME) if((timestamp[i++]=_fgetc(AVF))=='|') {timestamp[--i]=0;break;}
	
	// get 3bv
	while(_fgetc(AVF)!='B');	
	for(i=0;i<7;++i) cr[i]=0;
	i=0;
	while(c=_fgetc(AVF))
	{
		if (c=='T') break;
		cr[i]=c;i++;
	}
	bbbv=atoi(cr);

	// get time
	for(i=0;i<7;++i) cr[i]=0;
	i=0;
	while(c=_fgetc(AVF))
	{
		if (c=='.') break;
		cr[i]=c;i++;
	}
	score_sec=atoi(cr)-1;	// real time = time - 1
	for(i=0;i<7;++i) cr[i]=0;
	i=0;
	while(c=_fgetc(AVF))
	{
		if (c==']') break;
		cr[i]=c;i++;
	}
	score_ths=atoi(cr);	
	
	for(i=0;i<7;++i) cr[i]=0;
	while(cr[2]!=1 || cr[1]>1)	// cr[2]=time=1; cr[1]=position_x div 256<=30*16 div 256 = 1
	{
		cr[0]=cr[1];cr[1]=cr[2];cr[2]=_fgetc(AVF);
	}
	for(i=3;i<8;++i) cr[i]=_fgetc(AVF);
	
	// events: gogogo
	while(1)
	{
		video[cur].mouse=cr[0];
		video[cur].x=(int)cr[1]*256+cr[3];
		video[cur].y=(int)cr[5]*256+cr[7];
		video[cur].sec=(int)cr[6]*256+cr[2]-1;
		video[cur].hun=cr[4];
		
		if(video[cur].sec<0) break;
		
		for(i=0;i<8;++i) cr[i]=_fgetc(AVF);	
		++cur;
	}
	size=cur;
	
	// let's find player's name
	// 'cs='
	for(i=0;i<3;++i) cr[i]=0;
	while(cr[0]!='c' || cr[1]!='s' || cr[2]!='=')
	{
		cr[0]=cr[1];cr[1]=cr[2];cr[2]=_fgetc(AVF);
	}
	if(fs)
	{
		for(i=0;i<cur;++i)
		{
			video[i].ths=_fgetc(AVF)&0xF;
		}
		for(i=0;i<17;++i) _fgetc(AVF);
		while(_fgetc(AVF)!=13);
	}
	else
		for(i=0;i<17;++i) _fgetc(AVF);
	
	skin[0]=0;
	while(1)
	{
		getpair(AVF,name,value);
//		printf("Got pair:\n");
//		printf(" %s : %s\n",name,value);
		if(value[0])	// still not name
		{
			if(!strcmp(name,"Skin"))
			{
				strcpy(skin,value);
			}
		}
		else break;
	}
	// wow, it was name!
	// and now it must be program
	i=0;
	while(i<MAXNAME) 
		if((program[i++]=_fgetc(AVF))=='0' || (fs && program[i-1]>='0' && program[i-1]<='9')) 
		{
			if(fs) --i;
			program[--i]=0;
			break;
		}
	
	return 1;
}

void writetxt()
{
	int i,j;
	int curx,cury;
	const char* level_names[]={"null","beginner","intermediate","expert","custom"};
	const char* mode_names[]={"null","classic","classic","classic","density"};
	
	printf("RawVF_Version: Rev5\n");
	printf("Program: %s\n",program);
	if(!fs) printf("Version: 0.%d\n",ver); else printf("Version: R%d\n",ver);
	printf("Player: %s\n",name);
	printf("Timestamp: %s\n",timestamp);
	printf("Level: %s\n",level_names[mode]);
	printf("Width: %d\n",w);
	printf("Height: %d\n",h);
	printf("Mines: %d\n",m);
	if(skin[0]) printf("Skin: %s\n",skin);
	if(!l)
		printf("Mode: %s\n",mode_names[mode]);
	else
	{
		printf("Mode: %s\n","lucky");
		if(ll) printf("LuckLibrary: %s\n","on");
		if(ls) printf("LuckSolver: %s\n","on");
	}
	printf("Time: %d.%02d\n",score_sec,score_ths);
	printf("3BV: %d\n",bbbv);
	if(qm) printf("QuestionMarks: on\n");
	printf("Board:\n");
	for(i=0;i<h;++i)
	{
		for(j=0;j<w;++j)
			if(board[i*w+j])
				printf("*");
			else
				printf("0");
		printf("\n");
	}
	printf("Events:\n");
	curx=cury=-1;
	for(i=0;i<size;++i)
	{
		if(video[i].mouse==1 && video[i].x==curx && video[i].y==cury) continue;
		curx=video[i].x;cury=video[i].y;
		if(fs)
			printf("%d.%02d%01d ",video[i].sec,video[i].hun,video[i].ths);
		else
			printf("%d.%02d ",video[i].sec,video[i].hun);
		if(video[i].mouse==1) 
			printf("mv ");
		else if(video[i].mouse==3) 
			printf("lc ");
		else if(video[i].mouse==5) 
			printf("lr ");
		else if(video[i].mouse==9) 
			printf("rc ");
		else if(video[i].mouse==17) 
			printf("rr ");
		else if(video[i].mouse==33) 
			printf("mc ");
		else if(video[i].mouse==65) 
			printf("mr ");
		else if(video[i].mouse==145)
			printf("rr ");
		else if(video[i].mouse==193)
			printf("mr ");
		else if(video[i].mouse==11)
			printf("sc ");
		else if(video[i].mouse==21)
			printf("lr ");
		printf("%d %d (%d %d)\n",video[i].x/16+1,video[i].y/16+1,video[i].x,video[i].y);
	}
	
}

int main(int argc,char** argv)
{
	if(argc<2)
	{
		printf("Usage: %s <input avf> [nopause]\n",argv[0]);
		pause();
		return 0;
	}
	
	AVF=fopen(argv[1],"rb");
	
	if(!AVF)
	{
		printf("Couldn't open avf\n");
		return 1;
	}
	
	if(!readavf())
	{
		printf("Invalid AVF\n");
		return 1;
	}
	writetxt();
	fclose(AVF);free(board);
	
	if(argc==2) pause();
	return 0;
}

