#include "board.h"

using namespace std;




/** Display the entire board */
void Board::displayMoves  (ostream& out, BoardPosition pos) const
{
  const Pieces* piece = pieceAt(pos);
  for (int row = 8; row > 0; --row)
    {
      out << row << "|";
      for (char col = 'a'; col <= 'h'; ++col)
	{
	  if (piece != 0
	      && piece->canMoveTo(BoardPosition(col,row), *this))
	    {
	      out << '@';
	    }
	  else
	    {
	      const Pieces* p = pieceAt(BoardPosition(col, row));
	      if (p == 0)
		out << '.';
	      else
		out << p->symbol();
	    }
	}
      out << "\n";
    }
  out << "  --------\n  abcdefgh" << endl;
}
