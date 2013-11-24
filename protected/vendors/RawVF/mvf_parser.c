/*************************
* mvf to rawvf convertor *
* version 0.1            *
*************************/

#include <stdio.h>
#include <math.h>
#include <stdlib.h>
#include <string.h>
#define VERSION 0.1

#define MAXREP 100000
#define MAXNAME 1000
#define MAXEV 10

struct event
{
    int sec,ths;
    int x,y;
    int lb,rb,mb;
    int weirdness_bit;  /* pre-0.97 MVFs have a weird byte in their events which is seemingly always = 0
                           this bit is set when it is not */
};
typedef struct event event;

FILE* MVF;
FILE* RAWVF;

int mode,level,w,h,m;
int size; /* number of events */
int* board;
int* board_state;
int qm;
int has_date;
int month,year,day,hour,minute,second;
int has_info;
int bbbv,lcl,rcl,dcl,solved_bbbv;
int score_sec,score_ths;
/*int pause_allowed;*/
char name[MAXNAME];
const char program[]="Minesweeper Clone";
char *version;
char timestamp[MAXNAME];
event video[MAXREP];
const int square_size=16;
int fv=0;

void pause()
{
}

void error(const char* msg)
{
    fprintf(stderr,"Offset %d : %s\n",(int)ftell(MVF),msg);
/*  pause();*/
    exit(1);
}

unsigned char _fgetc(FILE* f)
{
    if(!feof(f)) return fgetc(f); else
    {
        error("Unexpected end of file");
        exit(1);
    }
}

int getint2(FILE* f)
{
    unsigned char c[2];
    c[0]=_fgetc(f);c[1]=_fgetc(f);
    return (int)c[1]+c[0]*256;
}

int getint3(FILE* f)
{
    unsigned char c[3];
    c[0]=_fgetc(f);c[1]=_fgetc(f);c[2]=_fgetc(f);
    return (int)c[2]+c[1]*256+c[0]*65536;
}

void read_board(int add)
{
    unsigned char c;
    int board_sz,i,pos;
    w=_fgetc(MVF);h=_fgetc(MVF);
    board=(int*)malloc((board_sz=w*h)*sizeof(int));
    for(i=0;i<board_sz;++i) board[i]=0;
    c=_fgetc(MVF);m=c<<8;
    c=_fgetc(MVF);m+=c;
    for(i=0;i<m;++i)
    {
        c=fgetc(MVF);pos=c+add;
        c=fgetc(MVF);pos+=(c+add)*w;
        if(pos>=board_sz || pos<0) error("Invalid mine position");
        board[pos]=1;
    }   
}

void read_event(int size,unsigned char* e)
{
    int i;
    for(i=0;i<size;++i) e[i]=_fgetc(MVF);
}

void read_score()
{
    unsigned char c,d;
    c=_fgetc(MVF);d=_fgetc(MVF);
    score_sec=c*256+d;
    score_ths=10*_fgetc(MVF);
}

int apply_perm(int num,int* byte,unsigned char* bit,unsigned char* event)
{
    return (event[byte[num]]&bit[num])?1:0;
}

int read_097()
{
    /* The 0.97 header contains date, level, mode, */
    /* score, 3bv and solved 3bv, number of clicks */
    unsigned char c;
    int len,i,j,cur;
    double leading;
    double num1,num2,num3;
    char s[41];
    int byte[40];
    unsigned char bit[40];
    const int mult=100000000;
    unsigned char e[5];
    
    has_date=has_info=1;
    month=(c=_fgetc(MVF));
    day=(c=_fgetc(MVF));
    year=getint2(MVF);
    hour=_fgetc(MVF);
    minute=_fgetc(MVF);
    second=_fgetc(MVF);
    level=_fgetc(MVF);
    mode=_fgetc(MVF);
    read_score();
    bbbv=getint2(MVF);
    solved_bbbv=getint2(MVF);
    lcl=getint2(MVF);
    dcl=getint2(MVF);
    rcl=getint2(MVF);
    qm=_fgetc(MVF);
    /* Now, the board and the player's name */
    read_board(-1);
    len=_fgetc(MVF);
    for(i=0;i<len;++i) name[i]=_fgetc(MVF);
    name[len]=0;
    
    /* The two leading bytes determine the permutation */
    leading=getint2(MVF);
    num1=sqrt(leading);
    num2=sqrt(leading+1000.0);
    num3=sqrt(num1+1000.0);
    sprintf(s,"%08d",(int)(lrint(fabs(cos(num3+1000.0)*mult))));
    sprintf(s+8,"%08d",(int)(lrint(fabs(sin(sqrt(num2))*mult))));
    sprintf(s+16,"%08d",(int)(lrint(fabs(cos(num3)*mult))));
    sprintf(s+24,"%08d",(int)(lrint(fabs(sin(sqrt(num1)+1000.0)*mult))));
    sprintf(s+32,"%08d",(int)(lrint(fabs(cos(sqrt(num2+1000.0))*mult))));
    s[40]=0;
    cur=0;
    for(i='0';i<='9';++i)
        for(j=0;j<40;++j)
            if(s[j]==i)
            {
                byte[cur]=j/8;
                bit[cur++]=1<<(j%8);
            }
    size=getint3(MVF);
    if(size>=MAXREP) error("Too large video");
    for(i=0;i<size;++i)
    {
        read_event(5,e);
        
        video[i].rb=apply_perm(0,byte,bit,e);
        video[i].mb=apply_perm(1,byte,bit,e);
        video[i].lb=apply_perm(2,byte,bit,e);
        video[i].x=video[i].y=video[i].ths=video[i].sec=0;
        for(j=0;j<9;++j)
        {
            video[i].x|=(apply_perm(12+j,byte,bit,e)<<j);
            video[i].y|=(apply_perm(3+j,byte,bit,e)<<j);
        }
        for(j=0;j<7;++j) video[i].ths|=(apply_perm(21+j,byte,bit,e)<<j);
        video[i].ths*=10;
        for(j=0;j<10;++j) video[i].sec|=(apply_perm(28+j,byte,bit,e)<<j);
    }
    return 1;
}

int read_2007()
{
    /* The 2007 header contains only date, level and mode */
    unsigned char c;
    int len,i,j,cur;
    int leading;
    double num1,num2,num3,num4;
    char s[49];
    int byte[48];
    unsigned char bit[48];
    const int mult=100000000;
    unsigned char e[6];
    
    has_date=1;has_info=0;
    month=(c=_fgetc(MVF));
    day=(c=_fgetc(MVF));
    year=getint2(MVF);
    hour=_fgetc(MVF);
    minute=_fgetc(MVF);
    second=_fgetc(MVF);
    level=_fgetc(MVF);
    mode=_fgetc(MVF);
    score_ths=getint3(MVF);
    score_sec=score_ths/1000;
    score_ths%=1000;
    qm=_fgetc(MVF);
    /* Now, the board and the player's name */
    read_board(-1);
    len=_fgetc(MVF);
    if(len>=MAXNAME) len=MAXNAME-1;
    for(i=0;i<len;++i) name[i]=_fgetc(MVF);
    name[len]=0;
    
    /* The two leading bytes determine the permutation */
    leading=getint2(MVF);
    num1=sqrt(leading);
    num2=sqrt(leading+1000.0);
    num3=sqrt(num1+1000.0);
    num4=sqrt(num2+1000.0);
    sprintf(s,"%08d",(int)(lrint(fabs(cos(num3+1000.0)*mult))));
    sprintf(s+8,"%08d",(int)(lrint(fabs(sin(sqrt(num2))*mult))));
    sprintf(s+16,"%08d",(int)(lrint(fabs(cos(num3)*mult))));
    sprintf(s+24,"%08d",(int)(lrint(fabs(sin(sqrt(num1)+1000.0)*mult))));
    sprintf(s+32,"%08d",(int)(lrint(fabs(cos(num4)*mult))));
    sprintf(s+40,"%08d",(int)(lrint(fabs(sin(num4)*mult))));
    s[48]=0;
    cur=0;
    for(i='0';i<='9';++i)
        for(j=0;j<48;++j)
            if(s[j]==i)
            {
                byte[cur]=j/8;
                bit[cur++]=1<<(j%8);
            }
    size=getint3(MVF);
    if(size>=MAXREP) error("Too large video");  
    for(i=0;i<size;++i)
    {
        read_event(6,e);
        
        video[i].rb=apply_perm(0,byte,bit,e);
        video[i].mb=apply_perm(1,byte,bit,e);
        video[i].lb=apply_perm(2,byte,bit,e);
        video[i].x=video[i].y=video[i].ths=video[i].sec=0;
        for(j=0;j<11;++j)
        {
            video[i].x|=(apply_perm(14+j,byte,bit,e)<<j);
            video[i].y|=(apply_perm(3+j,byte,bit,e)<<j);
        }
        for(j=0;j<22;++j) video[i].ths|=(apply_perm(25+j,byte,bit,e)<<j);
        video[i].sec=video[i].ths/1000;
        video[i].ths%=1000;
        video[i].x-=32;
        video[i].y-=32;
    }
    return 1;
}

int readmvf()
{
    /* First, we determine which MVF version it is.
       Starting with Clone 0.97, MVF begins with 0x11 0x4D.
       Pre-0.97 MVF can't begin with 0x11 0x42, 
       because these bytes are then width and height,
       and pre-0.97 Clone didn't save density videos.*/
    unsigned char c,d;
    
    size=0;
    c=_fgetc(MVF);
    d=_fgetc(MVF);
    if(c==0x11 && d==0x4D)
    {
        /* So it is a newer MVF.
           Then we read the offset 27 byte. 
           It is a part of the string representing the year of the release
           "2005" means it's 0.97, "2006" or "2007" mean it is 2006 or 2007
           it also can be "2008" -- then it's from Abiu's "funny mode" release */
        fseek(MVF,27,SEEK_SET);
        c=_fgetc(MVF);
        if(c=='5')
        {
            /* In 0.97 videos relevant things start from the offset 74 */
            fseek(MVF,74,SEEK_SET);
            version="0.97";
            return read_097();
        }
        else if(c=='6' || c=='7')
        {
            fseek(MVF,53,SEEK_SET);
            c=_fgetc(MVF);
            version=(char*)malloc(c+1);fv=1;
            for(d=0;d<c;++d) version[d]=_fgetc(MVF);
            version[d]=0;
            /* In newest videos relevant things start from the offset 71 */
            fseek(MVF,71,SEEK_SET);
            return read_2007();
        }
        else if(c=='8')
        {
            /* In "funny mode" videos relevant things start from the offset 74 */
            fseek(MVF,74,SEEK_SET);
            version="funny mode";
            c=read_097();
            mode=3; /* all "funny mode" videos are UPK */
            return c;
        }
    }
    else if (c==0x00 && d==0x00)
    {
        /* lose some head info*/
        fseek(MVF,7,SEEK_SET);
        version="0.97 lost head";
        return read_097();
    }
    else
    {
        /* It is a pre-097 MVF */
        unsigned char e[8];
        unsigned char last;
        int i;
        long filesize,current,after_events;
        int has_name;
        
        /* It begins with the board */
        fseek(MVF,0,SEEK_SET);
        read_board(0);
        /* Then there's two bytes which are usually 0 */
        /* The first one is 0x01 when question marks are on */
        qm=c=_fgetc(MVF);
        c=_fgetc(MVF);

        /* Now we will try to determine player's name */
        /* First, we'll check if the file has the 0.96 checksum */
        /* According to Clone's readme.txt it was used since 0.8 */
        /* This checksum is a 20 bytes long weird hex mess */
        /* and the byte right before it, as well as the byte right after it, is 0 */
        /* If there is no such checksum, the file has a pre-0.8 checksum */
        /* It looks like a printf'ed 10-digit number */
        /* According to Clone's readme.txt and the Guestbook, */
        /* the player's name was introduced in 0.76 */
        /* Unfortunately, i haven't neither this version of Clone, nor videos written by this version */
        /* So for 0.76 videos i don't know for sure how to extract the player's name */

        current=ftell(MVF);
        fseek(MVF,0L,SEEK_END);
        filesize=ftell(MVF);
        fseek(MVF,filesize-1L,SEEK_SET);
        last=_fgetc(MVF);
        if(last)
        {
            /* the last byte != 0, therefore, it's version <0.8 */
            /* We assume that the length of player's name is less than 97 */
            /* Then the last three bytes of the name field should be spaces */
            /* Otherwise there would be the score */
            /* We assume score isn't that exact monstrous number (5140.20) */
            /* that would be represented by three spaces */
            /* If it is, bad luck */
            /* But then, since early Clones record only about 240 seconds of video */
            /* MVF is corrupt anyway in that very special case */
            fseek(MVF,filesize-13L,SEEK_SET);
            if(_fgetc(MVF)==' ' && _fgetc(MVF)==' ' && fgetc(MVF)==' ')
            {
                version="0.76";
                /* This is a guess: 100 bytes long name, 10 bytes for the checksum, 3 bytes for score */
                /* There is no guarantee 0.76 doesn't actually contain some bytes between or after */
                after_events=filesize-113L;
                has_name=1;
            }
            else /* No name */
            {
                name[0]=0;
                version="<=0.75";
                after_events=filesize-13L;
                has_name=0;
            }
        }
        else
        {
            version="<=0.96";
            after_events=filesize-125L;
            has_name=1;
        }
        has_info=has_date=0;
        mode=1; /* Early Clone versions don't save any videos but classic */
        if(w==8 && h==8) level=1;
        else if(w==16 && h==16) level=2;
        else if(w==30 && h==16) level=3;
        else error("Invalid board size");

        fseek(MVF,after_events,SEEK_SET);
        read_score();
        if(has_name)
        {
            for(i=0;i<100;++i) name[i]=_fgetc(MVF);
            /* Now delete the trailing spaces */
            name[i]=0;
            while(i>=0 && name[--i]==' ') name[i]=0;
        }
        
        /* Now we're ready to read the events */
        fseek(MVF,current,SEEK_SET);
        while(current<=after_events)
        {
            read_event(8,e);
                        
            video[size].sec=e[0];
            video[size].ths=e[1]*10;

            if(size>0 && 
            (video[size].sec<video[size-1].sec || 
            (video[size].sec==video[size-1].sec && video[size].ths<video[size-1].ths))) 
            {
                break;
            }
            if(video[size].sec>score_sec ||
            (video[size].sec==score_sec && video[size].ths>score_ths))
            {
                break;
            }
            
            video[size].lb=e[2]&0x01;
            video[size].mb=e[2]&0x02;
            video[size].rb=e[2]&0x04;
            video[size].x=(int)e[3]*256+e[4];
            video[size].y=(int)e[5]*256+e[6];
            video[size++].weirdness_bit=e[7];
            current+=8;
            if(size>=MAXREP) error("Too large video");
        }

        video[size].lb=video[size].mb=video[size].rb=0;
        video[size].x=video[size-1].x;
        video[size].y=video[size-1].y;
        video[size].sec=video[size-1].sec;
        video[size].ths=video[size-1].ths;
        ++size;
        if(size>=MAXREP) error("Too large video");
        return 1;
    }
    return 0;
}


void print_first_event(event* e)
{
    char* ev=0;
    if(e->lb) ev="lc";
    if(e->rb) ev="rc";
    if(e->mb) ev="mc";
    if(!ev) ev="mv";
    fprintf(RAWVF,"%d.%03d %s %d %d (%d %d)\n",
            e->sec,e->ths,  
            ev,
            e->x/square_size+1,e->y/square_size+1,
            e->x,e->y);
}

void print_event(event* e,event* prev_e)
{
    int num_events=0,i;
    char* evs[MAXEV];
/*  char* mouse_state[3]={"","",""};*/
    if(e->x!=prev_e->x || e->y!=prev_e->y) evs[num_events++]="mv";
    if(e->lb && !prev_e->lb) evs[num_events++]="lc";
    if(e->rb && !prev_e->rb) evs[num_events++]="rc";
    if(e->mb && !prev_e->mb) evs[num_events++]="mc";
    if(!e->lb && prev_e->lb) evs[num_events++]="lr";
    if(!e->rb && prev_e->rb) evs[num_events++]="rr";
    if(!e->mb && prev_e->mb) evs[num_events++]="mr";
/*  if(e->lb) mouse_state[0]="l";
    if(e->rb) mouse_state[1]="r";
    if(e->mb) mouse_state[2]="m";*/
    
    if(!num_events) return; /* this event doesn't do anything; why write it into the RawVF? */
    
    for(i=0;i<num_events;++i)
    {       
        fprintf(RAWVF,"%d.%03d %s %d %d (%d %d)\n",
                e->sec,e->ths,  
                evs[i],
                e->x/square_size+1,e->y/square_size+1,
                e->x,e->y);
    }
}

void writetxt()
{
    int i,j;
    const char* level_names[]={"","beginner","intermediate","expert","custom","custom"};
    const char* mode_names[]={"","classic","density","UPK","cheat"};
    
    if(level>5) level=5;
    if(mode>4) mode=4;
    
    fprintf(RAWVF,"RawVF_Version: Rev5\n");
    fprintf(RAWVF,"Program: %s\n",program);
    fprintf(RAWVF,"Version: %s\n",version);
    fprintf(RAWVF,"Player: %s\n",name);
    fprintf(RAWVF,"Level: %s\n",level_names[level]);
    fprintf(RAWVF,"Width: %d\n",w);
    fprintf(RAWVF,"Height: %d\n",h);
    fprintf(RAWVF,"Mines: %d\n",m);
    fprintf(RAWVF,"Mode: %s\n",mode_names[mode]);
    fprintf(RAWVF,"Time: %d.%03d\n",score_sec,score_ths);
    if(qm) fprintf(RAWVF,"Marks: on\n");
    if(has_date) 
    {
        if(day)
            fprintf(RAWVF,"Timestamp: %d-%02d-%02d %02d:%02d:%02d\n",year,month,day,hour,minute,second);
        else
            fprintf(RAWVF,"Timestamp: %d-%02d-?? %02d:%02d:%02d\n",year,month,hour,minute,second);
    }
    if(has_info)
    {
        fprintf(RAWVF,"3BV: %d\n",bbbv);
        fprintf(RAWVF,"Solved3BV: %d\n",solved_bbbv);
        fprintf(RAWVF,"LeftClicks: %d\n",lcl);
        fprintf(RAWVF,"RightClicks: %d\n",rcl);
        fprintf(RAWVF,"DoubleClicks: %d\n",dcl);
        
    }
    fprintf(RAWVF,"Board:\n");
    for(i=0;i<h;++i)
    {
        for(j=0;j<w;++j)
            if(board[i*w+j])
                fprintf(RAWVF,"*");
            else
                fprintf(RAWVF,"0");
        fprintf(RAWVF,"\n");
    }
    fprintf(RAWVF,"Events:\n");
    fprintf(RAWVF,"0.000 start\n");
    print_first_event(video);
    for(i=1;i<size;++i)
    {
        print_event(video+i,video+i-1);
    }
    
}

int main(int argc,char** argv)
{

    if(argc<2)
    {
        printf("Usage: mvf2rawvf <input> [output]\n");
        pause();
        return 8;
    }
    
    MVF=fopen(argv[1],"rb");
    if(!MVF)
    {
        printf("Can't open MVF\n");
        return 2;
    }
    if(argc>=3)
    {
        RAWVF=fopen(argv[2],"w+");
        if(!RAWVF)
        {
            printf("Can't open output file\n");
            return 3;
        }
    }
    else RAWVF=stdout;
    
    if(!readmvf())
    {
        printf("Invalid MVF\n");
        return 1;
    }
    writetxt();
    fclose(MVF);if(argc>=3) fclose(RAWVF);free(board);if(fv) free(version);
    
    return 0;
}

