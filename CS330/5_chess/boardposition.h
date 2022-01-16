#ifndef BOARDPOSITION
#define BOARDPOSITION

#include <iostream>

class BoardPosition
{
private:

  char column;   // in the range 'a'...'h'
  int row;       // in the range 1..8

public:

  BoardPosition(char theColumn = 'a', int theRow = 1)
    : row(theRow), column(theColumn)
    {}
 
  char getColumn() const  {return column;}
  int getRow() const      {return row;}


  BoardPosition up(int numRows) const
  {
    return BoardPosition(column, row+numRows);
  }

  BoardPosition down(int numRows) const
  {
    return BoardPosition(column, row-numRows);
  }

  BoardPosition left(int numCols) const
  {
    return BoardPosition((char)(column-numCols), row);
  }

  BoardPosition right(int numCols) const
  {
    return BoardPosition((char)(column+numCols), row);
  }


  bool operator== (BoardPosition bp)
  {
    return (column == bp.column) && (row == bp.row);
  }

  bool operator!= (BoardPosition bp)
  {
    return !operator==(bp);
  }
};

inline
std::ostream& operator<< (std::ostream& out, BoardPosition bp)
{
  out << bp.getColumn() << bp.getRow();
  return out;
}

#endif

