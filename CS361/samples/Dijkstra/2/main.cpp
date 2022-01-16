 #include <map>
 #include <queue>
 using namespace std;

 #define X first
 #define Y second

 template <class Node, class Edge=int> class Dijkstra {
    public:
    Dijkstra() {}
    Dijkstra(const Node &n, const Edge &e=0) { push(n, e); }
    bool empty() const { return q.empty(); }
    void push(const Node &n, const Edge &e=0) {
       Iter it = m.find(n);
       if (it == m.end()) it = m.insert(make_pair(n, e)).X;
       else if (it->Y > e) it->Y = e;
       else return;
       q.push(make_pair(e, it));
    }
    const Node &pop() {
       cur = q.top().Y;
       do q.pop();
       while (!q.empty() && q.top().Y->Y < q.top().X);
       return cur->X;
    }
    const Edge &dist() const { return cur->Y; }
    void link(const Node &n, const Edge &e=1) { push(n, cur->Y + e); }

    private:
    typedef typename map<Node, Edge>::iterator Iter;
    typedef pair<Edge, Iter> Value;
    struct Rank : public binary_function<Value, Value, bool> {
       bool operator()(const Value& x, const Value& y) const {
          return x.X > y.X;
       }
    };
    map<Node, Edge> m;
    priority_queue<Value, vector<Value>, Rank> q;
    Iter cur;
 };

 // Example usage (nodes and edges are represented with ints)
 int shortestDistance(int nodes, int startNode, int endNode, int **dists) {
    Dijkstra<int> dijkstra(startNode);
    while (!dijkstra.empty()) {
       int curNode = dijkstra.pop();
       if (curNode == endNode)
          return dijkstra.dist();
       for (int i = 0; i < nodes; i++)
          if (dists[curNode][i] >= 0) // "i" is a neighbor of curNode
             dijkstra.link(i, dists[curNode][i]); // add weighted edge
    }
    return -1; // No path found
 }

 /*int main()
 {

 }*/
