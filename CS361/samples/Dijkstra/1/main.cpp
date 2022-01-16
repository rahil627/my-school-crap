//pre-processors----------------------------------------------------------------------------------
#include <iostream>
#include <vector>
#include <string>
#include <map>//<<simple graph type definition headers>>=
#include <list>//<<simple graph type definition headers>>=
#include <set>//<<simple graph type definition headers>>=
//using namespace std;
//#include ""

//<<simple graph types>>=
typedef int vertex_t;
typedef double weight_t;
struct edge {
    vertex_t target;
    weight_t weight;
    edge(vertex_t arg_target, weight_t arg_weight)
        : target(arg_target), weight(arg_weight) { }
};

//<<simple graph types>>=
typedef std::map<vertex_t, std::list<edge> > adjacency_map_t;

//<<pair first less comparator>>=
template <typename T1, typename T2>
struct pair_first_less
{
    bool operator()(std::pair<T1,T2> p1, std::pair<T1,T2> p2)
    {
        return p1.first < p2.first;
    }
};

//<<visit each vertex u, always visiting vertex with smallest min_distance first>>=
std::set< std::pair<weight_t, vertex_t>,
          pair_first_less<weight_t, vertex_t> > vertex_queue;
for (adjacency_map_t::iterator vertex_iter = adjacency_map.begin();
     vertex_iter != adjacency_map.end();
     vertex_iter++)
{
    vertex_t v = vertex_iter->first;
    vertex_queue.insert(std::pair<weight_t, vertex_t>(min_distance[v], v));
}

while (!vertex_queue.empty()) {
    vertex_t u = vertex_queue.begin()->second;
    vertex_queue.erase(vertex_queue.begin());}

//<<simple compute paths function>>=
void DijkstraComputePaths(vertex_t source,
                          adjacency_map_t& adjacency_map,
                          std::map<vertex_t, weight_t>& min_distance,
                          std::map<vertex_t, vertex_t>& previous)
{
    initialize output parameters
    min_distance[source] = 0;
    visit each vertex u, always visiting vertex with smallest min_distance first
        // Visit each edge exiting u
        for (std::list<edge>::iterator edge_iter = adjacency_map[u].begin();
             edge_iter != adjacency_map[u].end();
             edge_iter++)
        {
            vertex_t v = edge_iter->target;
            weight_t weight = edge_iter->weight;
            relax the edge (u,v)
        }
    }
}

//<<get shortest path function>>=
std::list<vertex_t> DijkstraGetShortestPathTo(
    vertex_t target, std::map<vertex_t, vertex_t>& previous)
{
    std::list<vertex_t> path;
    std::map<vertex_t, vertex_t>::iterator prev;
    vertex_t vertex = target;
    path.push_front(vertex);
    while((prev = previous.find(vertex)) != previous.end())
    {
        vertex = prev->second;
        path.push_front(vertex);
    }
    return path;
}


//function prototypes-----------------------------------------------------------------------------


//main--------------------------------------------------------------------------------------------
int main()
{
    adjacency_map_t adjacency_map;
    std::vector<std::string> vertex_names;

    //<<initialize adjacency map>>=
    vertex_names.push_back("Harrisburg");   // 0
    vertex_names.push_back("Baltimore");    // 1
    vertex_names.push_back("Washington");   // 2
    vertex_names.push_back("Philadelphia"); // 3
    vertex_names.push_back("Binghamton");   // 4
    vertex_names.push_back("Allentown");    // 5
    vertex_names.push_back("New York");     // 6
    adjacency_map[0].push_back(edge(1,  79.83));
    adjacency_map[0].push_back(edge(5,  81.15));
    adjacency_map[1].push_back(edge(0,  79.75));
    adjacency_map[1].push_back(edge(2,  39.42));
    adjacency_map[1].push_back(edge(3, 103.00));
    adjacency_map[2].push_back(edge(1,  38.65));
    adjacency_map[3].push_back(edge(1, 102.53));
    adjacency_map[3].push_back(edge(5,  61.44));
    adjacency_map[3].push_back(edge(6,  96.79));
    adjacency_map[4].push_back(edge(5, 133.04));
    adjacency_map[5].push_back(edge(0,  81.77));
    adjacency_map[5].push_back(edge(3,  62.05));
    adjacency_map[5].push_back(edge(4, 134.47));
    adjacency_map[5].push_back(edge(6,  91.63));
    adjacency_map[6].push_back(edge(3,  97.24));
    adjacency_map[6].push_back(edge(5,  87.94));

    std::map<vertex_t, weight_t> min_distance;
    std::map<vertex_t, vertex_t> previous;
    DijkstraComputePaths(0, adjacency_map, min_distance, previous);

    //<<print out shortest paths and distances>>=
    for (adjacency_map_t::iterator vertex_iter = adjacency_map.begin();
     vertex_iter != adjacency_map.end();
     vertex_iter++)
{
    vertex_t v = vertex_iter->first;
    std::cout << "Distance to " << vertex_names[v] << ": " << min_distance[v] << std::endl;
    std::list<vertex_t> path =
        DijkstraGetShortestPathTo(v, previous);
    std::list<vertex_t>::iterator path_iter = path.begin();
    std::cout << "Path: ";
    for( ; path_iter != path.end(); path_iter++)
    {
        std::cout << vertex_names[*path_iter] << " ";
    }
    std::cout << std::endl;
}

	return 0;
}

//functions---------------------------------------------------------------------------------------
