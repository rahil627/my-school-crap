#include "pieces.h"

using namespace std;


void Pieces::moveTo (BoardPosition newPosition)
{
  _hasMoved = true;
  _position = newPosition;
}
