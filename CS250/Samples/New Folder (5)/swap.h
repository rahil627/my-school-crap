template <class TParam>
void Swap( TParam &x, TParam &y)
{
      TParam temp;
      temp = x;
      x = y;
      y = temp;

}

template <class TParam>
void display(TParam T2, TParam T3)
      {
      cout<<"first = "<<T2<<"  second = "<<T3<<endl;
}

template <class TParam>
void show(TParam T1, TParam T2)
      {     
      T1.display();
      T2.display();
      }
